<?php

declare(strict_types=1);

namespace Grader\Support;

use PHPUnit\Framework\Assert;

/**
 * Calls a function defined in a learner's file with inputs the exercise did not show,
 * so the grader checks the function's contract instead of one fixed output.
 *
 * Each call runs in a separate PHP process (see call-function.php) with strict types.
 */
final class LearnerFunction
{
    private const TIMEOUT_SECONDS = 5;

    /** Returns what the function returned, or fails the test with a readable message. */
    public static function returnValue(string $relativePath, string $function, array $arguments): mixed
    {
        $result = self::call($relativePath, $function, $arguments);
        $call = self::describeCall($function, $arguments);

        if ($result['status'] === 'thrown') {
            Assert::fail("{$call} di {$relativePath} berhenti dengan {$result['class']}: {$result['message']}");
        }

        return $result['value'];
    }

    /** Fails unless calling the function throws an error of the given class (for example TypeError). */
    public static function assertThrows(
        string $relativePath,
        string $function,
        array $arguments,
        string $expectedClass,
        string $reason,
    ): void {
        $result = self::call($relativePath, $function, $arguments);
        $call = self::describeCall($function, $arguments);

        Assert::assertSame(
            'thrown',
            $result['status'],
            "{$call} seharusnya berhenti dengan {$expectedClass}, tapi fungsi itu mengembalikan nilai. {$reason}",
        );
        Assert::assertSame(
            $expectedClass,
            $result['class'],
            "{$call} seharusnya berhenti dengan {$expectedClass}, bukan {$result['class']}. {$reason}",
        );
    }

    /** @return array{status: string, value?: mixed, class?: string, message?: string} */
    private static function call(string $relativePath, string $function, array $arguments): array
    {
        $command = [
            PHP_BINARY,
            '-d', 'display_errors=stderr',
            '-d', 'log_errors=0',
            '-d', 'error_reporting=E_ALL',
            __DIR__ . '/call-function.php',
            Paths::backend($relativePath),
            $function,
            serialize($arguments),
        ];

        $pipes = [];
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, Paths::backend(''));

        if (!is_resource($process)) {
            Assert::fail("Grader gagal menjalankan {$relativePath}.");
        }

        stream_set_timeout($pipes[1], self::TIMEOUT_SECONDS);
        $stdout = (string) stream_get_contents($pipes[1]);
        $stderr = trim((string) stream_get_contents($pipes[2]));
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        if ($stderr !== '') {
            Assert::fail("PHP menampilkan warning atau error saat memuat {$relativePath}:\n{$stderr}");
        }

        $result = @unserialize($stdout, ['allowed_classes' => false]);

        if (!is_array($result)) {
            Assert::fail("Grader tidak bisa membaca hasil pemanggilan fungsi di {$relativePath}.");
        }

        if ($result['status'] === 'load-error') {
            Assert::fail(
                "{$relativePath} berhenti dengan {$result['class']} saat dijalankan: {$result['message']}",
            );
        }

        if ($result['status'] === 'missing') {
            Assert::fail(
                "Fungsi {$function}() tidak ditemukan di {$relativePath}. Periksa ejaan nama fungsinya.",
            );
        }

        return $result;
    }

    private static function describeCall(string $function, array $arguments): string
    {
        $parts = [];

        foreach ($arguments as $argument) {
            $parts[] = is_array($argument)
                ? json_encode($argument, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : var_export($argument, true);
        }

        return $function . '(' . implode(', ', $parts) . ')';
    }
}
