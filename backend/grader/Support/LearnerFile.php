<?php

declare(strict_types=1);

namespace Grader\Support;

use PHPUnit\Framework\Assert;

/**
 * Checks on the learner's source file itself (exists, not empty, uses a variable, ...).
 * These only check what the exercise contract asks for, never how to solve it.
 */
final class LearnerFile
{
    public static function assertFilled(string $relativePath, string $exercise): string
    {
        $path = Paths::backend($relativePath);

        if (!is_file($path)) {
            Assert::fail(
                "File {$relativePath} tidak ditemukan. File ini dibuat mentor; jangan dihapus atau diganti nama.",
            );
        }

        $source = (string) file_get_contents($path);

        if (trim($source) === '') {
            Assert::fail("File {$relativePath} masih kosong. Kerjakan {$exercise} dulu, lalu jalankan grader lagi.");
        }

        return $source;
    }

    public static function assertUsesVariable(string $source, string $variable, string $relativePath): void
    {
        $found = false;

        foreach (token_get_all($source) as $token) {
            if (is_array($token) && $token[0] === T_VARIABLE && $token[1] === $variable) {
                $found = true;
                break;
            }
        }

        Assert::assertTrue(
            $found,
            "Kontrak meminta variabel {$variable} di {$relativePath}, tapi variabel itu tidak ditemukan. "
            . 'Periksa ejaan dan huruf besar kecilnya.',
        );
    }

    public static function assertTextAppearsOnce(string $source, string $text, string $reason): void
    {
        Assert::assertSame(
            1,
            substr_count($source, $text),
            "Teks \"{$text}\" harus muncul tepat satu kali di file. {$reason}",
        );
    }

    public static function assertNoWarnings(CliResult $result, string $relativePath): void
    {
        if (trim($result->stderr) !== '') {
            Assert::fail(
                "PHP menampilkan warning atau error saat menjalankan {$relativePath}:\n" . trim($result->stderr),
            );
        }
    }
}
