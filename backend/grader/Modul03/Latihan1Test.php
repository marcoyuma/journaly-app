<?php

declare(strict_types=1);

namespace Grader\Modul03;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    private const FILE = 'playground/03-latihan-1.php';

    #[TestDox('Latihan 1: memakai array $journal dan setiap nilai ditulis sekali')]
    public function testUsesArray(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 1');
        $reason = 'Nilai disimpan sekali di array $journal, lalu dicetak lewat $journal[\'key\'].';

        LearnerFile::assertUsesVariable($source, '$journal', self::FILE);
        LearnerFile::assertTextAppearsOnce($source, 'Belajar array', $reason);
        LearnerFile::assertTextAppearsOnce($source, 'Array menyimpan banyak nilai.', $reason);
        LearnerFile::assertTextAppearsOnce($source, '2026-09-25', $reason);
    }

    #[TestDox('Latihan 1: mencetak jurnal persis sesuai kontrak')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "#7 Belajar array\nArray menyimpan banyak nilai.\nDibuat: 2026-09-25\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 1.',
        );
    }
}
