<?php

declare(strict_types=1);

namespace Grader\Support;

use RuntimeException;

/**
 * Runs a learner's PHP script as a separate process and captures what it prints.
 *
 * Warnings and errors are sent to stderr (not mixed into stdout), so the grader
 * can report them separately from the expected output.
 */
final class Cli
{
    private const TIMEOUT_SECONDS = 5;

    public static function run(string $relativePath): CliResult
    {
        $path = Paths::backend($relativePath);

        $command = [
            PHP_BINARY,
            '-d', 'display_errors=stderr',
            '-d', 'log_errors=0',
            '-d', 'error_reporting=E_ALL',
            $path,
        ];

        $pipes = [];
        $process = proc_open(
            $command,
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            Paths::backend(''),
        );

        if (!is_resource($process)) {
            throw new RuntimeException("Grader gagal menjalankan {$relativePath}.");
        }

        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $deadline = microtime(true) + self::TIMEOUT_SECONDS;

        while (true) {
            $stdout .= (string) stream_get_contents($pipes[1]);
            $stderr .= (string) stream_get_contents($pipes[2]);
            $status = proc_get_status($process);

            if (!$status['running']) {
                $stdout .= (string) stream_get_contents($pipes[1]);
                $stderr .= (string) stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                return new CliResult($stdout, $stderr, $status['exitcode']);
            }

            if (microtime(true) > $deadline) {
                proc_terminate($process);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                throw new RuntimeException(
                    "{$relativePath} berjalan lebih dari " . self::TIMEOUT_SECONDS . ' detik dan dihentikan.',
                );
            }

            usleep(10_000);
        }
    }
}
