<?php

declare(strict_types=1);

namespace Grader\Support;

final class Paths
{
    /** Absolute path inside backend/, e.g. Paths::backend('playground/01-latihan-1.php'). */
    public static function backend(string $relativePath): string
    {
        $root = dirname(__DIR__, 2);

        return $relativePath === '' ? $root : $root . '/' . $relativePath;
    }

    /** Absolute path of the repository root (the folder that holds backend/ and frontend/). */
    public static function repository(): string
    {
        return dirname(__DIR__, 3);
    }
}
