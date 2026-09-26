<?php

declare(strict_types=1);

namespace Grader\Modul03;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    private const FILE = 'playground/03-latihan-2.php';

    #[TestDox('Latihan 2: memakai $journals, foreach, dan count()')]
    public function testUsesLoopAndCount(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 2');
        $reason = 'Setiap judul ditulis sekali di $journals, lalu dicetak oleh foreach.';

        LearnerFile::assertUsesVariable($source, '$journals', self::FILE);
        LearnerFile::assertTextAppearsOnce($source, 'Hari pertama', $reason);
        LearnerFile::assertTextAppearsOnce($source, 'Belajar array', $reason);
        LearnerFile::assertTextAppearsOnce($source, 'Libur panjang', $reason);
        LearnerFile::assertCallsFunction($source, 'count', 'Jumlah jurnal dihitung dari $journals, bukan ditulis manual.');
    }

    #[TestDox('Latihan 2: mencetak daftar jurnal dan totalnya persis sesuai kontrak')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "1. Hari pertama\n2. Belajar array\n3. Libur panjang\nTotal: 3 jurnal\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 2.',
        );
    }
}
