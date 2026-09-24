<?php

declare(strict_types=1);

namespace Grader\Support;

final class CliResult
{
    public function __construct(
        public readonly string $stdout,
        public readonly string $stderr,
        public readonly int $exitCode,
    ) {
    }
}
