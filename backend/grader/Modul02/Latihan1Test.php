<?php

declare(strict_types=1);

namespace Grader\Modul02;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use Grader\Support\LearnerFunction;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    private const FILE = 'playground/02-latihan-1.php';

    #[TestDox('Latihan 1: countLabel(0) mengembalikan "Belum ada jurnal"')]
    public function testZero(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $this->assertSame(
            'Belum ada jurnal',
            LearnerFunction::returnValue(self::FILE, 'countLabel', [0]),
            'countLabel(0) belum mengembalikan string yang diminta kontrak.',
        );
    }

    #[TestDox('Latihan 1: countLabel dengan angka lain mengembalikan "<angka> jurnal"')]
    public function testOtherNumbers(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        foreach ([1 => '1 jurnal', 12 => '12 jurnal'] as $count => $expected) {
            $this->assertSame(
                $expected,
                LearnerFunction::returnValue(self::FILE, 'countLabel', [$count]),
                "countLabel({$count}) belum mengembalikan \"{$expected}\". Pakai parameter \$count, jangan menulis angkanya langsung.",
            );
        }
    }

    #[TestDox('Latihan 1: mencetak hasil countLabel(0) dan countLabel(3)')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "Belum ada jurnal\n3 jurnal\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 1.',
        );
    }
}
