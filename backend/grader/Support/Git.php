<?php

declare(strict_types=1);

namespace Grader\Support;

/**
 * Read-only git queries against the repository, used by exercises about saving work.
 * The grader never changes git state.
 */
final class Git
{
    /** Runs `git -C <repo> <args...>` and returns [exit code, trimmed stdout]. */
    public static function run(string ...$args): array
    {
        $command = ['git', '-C', Paths::repository(), ...$args];
        $pipes = [];
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (!is_resource($process)) {
            return [-1, ''];
        }

        $stdout = (string) stream_get_contents($pipes[1]);
        stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), trim($stdout)];
    }

    public static function currentBranch(): string
    {
        return self::run('branch', '--show-current')[1];
    }

    public static function isTracked(string $pathFromRepoRoot): bool
    {
        return self::run('ls-files', '--error-unmatch', $pathFromRepoRoot)[0] === 0;
    }

    public static function hasUncommittedChanges(string $pathFromRepoRoot): bool
    {
        return self::run('status', '--porcelain', '--', $pathFromRepoRoot)[1] !== '';
    }

    public static function branchExists(string $branch): bool
    {
        return self::run('branch', '--list', $branch)[1] !== '';
    }
}
