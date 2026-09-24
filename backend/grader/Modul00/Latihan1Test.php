<?php

declare(strict_types=1);

namespace Grader\Modul00;

use Grader\Support\Git;
use Grader\Support\Paths;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    #[TestDox('Latihan 1: PHP yang dipakai versi 8.5 atau lebih baru')]
    public function testPhpVersion(): void
    {
        $this->assertTrue(
            version_compare(PHP_VERSION, '8.5.0', '>='),
            'Versi PHP Anda ' . PHP_VERSION . '. Kurikulum ini butuh PHP 8.5 atau lebih baru (brew upgrade php).',
        );
    }

    #[TestDox('Latihan 1: ekstensi pdo_mysql, mbstring, dan curl aktif')]
    public function testExtensions(): void
    {
        foreach (['pdo_mysql', 'mbstring', 'curl'] as $extension) {
            $this->assertTrue(
                extension_loaded($extension),
                "Ekstensi PHP {$extension} tidak aktif. Cek dengan: php -m",
            );
        }
    }

    #[TestDox('Latihan 1: composer install sudah dijalankan (composer.lock ada)')]
    public function testComposerLockExists(): void
    {
        $this->assertFileExists(
            Paths::backend('composer.lock'),
            'composer.lock belum ada. Jalankan: composer install (di folder backend).',
        );
    }

    #[TestDox('Latihan 1: folder vendor/ diabaikan git sehingga tidak ikut ter-commit')]
    public function testVendorIsIgnored(): void
    {
        $this->assertSame(
            0,
            Git::run('check-ignore', '-q', 'backend/vendor/')[0],
            'backend/vendor/ tidak diabaikan git. Pastikan .gitignore di root repo berisi baris backend/vendor/.',
        );
    }
}
