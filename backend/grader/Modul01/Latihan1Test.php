<?php

declare(strict_types=1);

namespace Grader\Modul01;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    private const FILE = 'playground/01-latihan-1.php';

    #[TestDox('Latihan 1: mencetak nama aplikasi dan tagline, masing-masing satu baris')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "Journaly\nCatatan harian pribadi\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 1 (perhatikan huruf besar kecil dan \n di akhir setiap baris).',
        );
    }
}
