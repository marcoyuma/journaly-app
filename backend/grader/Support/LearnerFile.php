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

    public static function assertDeclaresStrictTypes(string $source, string $relativePath): void
    {
        $code = [];

        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, T_OPEN_TAG], true)) {
                continue;
            }

            $code[] = is_array($token) ? $token[1] : $token;

            if (count($code) === 7) {
                break;
            }
        }

        Assert::assertTrue(
            implode('', $code) === 'declare(strict_types=1);',
            "Kontrak meminta {$relativePath} diawali declare(strict_types=1); tepat di bawah <?php.",
        );
    }

    public static function assertCallsFunction(string $source, string $function, string $reason): void
    {
        $tokens = array_values(array_filter(
            token_get_all($source),
            static fn ($token): bool => !is_array($token) || $token[0] !== T_WHITESPACE,
        ));
        $found = false;

        foreach ($tokens as $index => $token) {
            if (is_array($token) && $token[0] === T_STRING && strtolower($token[1]) === $function
                && ($tokens[$index + 1] ?? null) === '(') {
                $found = true;
                break;
            }
        }

        Assert::assertTrue($found, "Kontrak meminta pemanggilan {$function}(). {$reason}");
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
