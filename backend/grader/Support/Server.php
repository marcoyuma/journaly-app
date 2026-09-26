<?php

declare(strict_types=1);

namespace Grader\Support;

use PHPUnit\Framework\Assert;
use RuntimeException;

/**
 * Starts PHP's built-in server (`php -S`) on a free port with the learner's file as the
 * router script, then sends real HTTP requests to it with ext-curl.
 *
 * Errors are not shown in the response body (display_errors=0). They are written to a
 * temporary log file instead, so each response carries the warnings its request caused.
 */
final class Server
{
    private const START_TIMEOUT_SECONDS = 5;
    private const REQUEST_TIMEOUT_SECONDS = 5;

    /** @param resource $process */
    private function __construct(
        private $process,
        private readonly int $port,
        private readonly string $errorLog,
        private readonly string $relativePath,
    ) {
    }

    public static function start(string $relativePath): self
    {
        $port = self::freePort();
        $errorLog = (string) tempnam(sys_get_temp_dir(), 'grader-php-errors-');

        $command = [
            PHP_BINARY,
            '-d', 'display_errors=0',
            '-d', 'html_errors=0',
            '-d', 'log_errors=1',
            '-d', "error_log={$errorLog}",
            '-d', 'error_reporting=E_ALL',
            '-S', "127.0.0.1:{$port}",
            Paths::backend($relativePath),
        ];

        $pipes = [];
        $process = proc_open(
            $command,
            [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes,
            Paths::backend(''),
        );

        if (!is_resource($process)) {
            throw new RuntimeException('Grader gagal menyalakan php -S.');
        }

        $deadline = microtime(true) + self::START_TIMEOUT_SECONDS;

        while (microtime(true) < $deadline) {
            $connection = @fsockopen('127.0.0.1', $port, $errorCode, $errorMessage, 0.1);

            if ($connection !== false) {
                fclose($connection);

                return new self($process, $port, $errorLog, $relativePath);
            }

            usleep(20_000);
        }

        proc_terminate($process);
        proc_close($process);

        throw new RuntimeException("php -S tidak menyala di port {$port} dalam " . self::START_TIMEOUT_SECONDS . ' detik.');
    }

    /** @param array<string, string> $headers */
    public function request(string $method, string $path, array $headers = [], ?string $body = null): HttpResponse
    {
        $logSizeBefore = (int) filesize($this->errorLog);

        $headerLines = [];
        foreach ($headers as $name => $value) {
            $headerLines[] = "{$name}: {$value}";
        }

        $curl = curl_init("http://127.0.0.1:{$this->port}{$path}");
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_TIMEOUT => self::REQUEST_TIMEOUT_SECONDS,
        ]);

        if ($body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $raw = curl_exec($curl);

        if ($raw === false) {
            Assert::fail("Request {$method} {$path} ke {$this->relativePath} gagal: " . curl_error($curl));
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        clearstatcache(true, $this->errorLog);
        $errors = (string) file_get_contents($this->errorLog, false, null, $logSizeBefore);
        $errors = trim((string) preg_replace('/^\[[^\]]*\] /m', '', $errors));

        return new HttpResponse(
            $method,
            $path,
            $status,
            self::parseHeaders(substr($raw, 0, $headerSize)),
            substr($raw, $headerSize),
            $errors,
        );
    }

    public function stop(): void
    {
        proc_terminate($this->process);
        proc_close($this->process);
        @unlink($this->errorLog);
    }

    private static function freePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');

        if ($socket === false) {
            throw new RuntimeException('Grader tidak menemukan port kosong.');
        }

        $name = (string) stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($name, strrpos($name, ':') + 1);
    }

    /** @return array<string, string> Lowercase header name => value. */
    private static function parseHeaders(string $rawHeaders): array
    {
        $headers = [];

        foreach (explode("\r\n", $rawHeaders) as $line) {
            $colon = strpos($line, ':');

            if ($colon !== false) {
                $headers[strtolower(substr($line, 0, $colon))] = trim(substr($line, $colon + 1));
            }
        }

        return $headers;
    }
}
