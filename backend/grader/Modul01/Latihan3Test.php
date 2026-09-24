<?php

declare(strict_types=1);

namespace Grader\Modul01;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan3Test extends TestCase
{
    private const FILE = 'playground/01-latihan-3.php';

    #[TestDox('Latihan 3: memakai variabel $title, $content, dan $createdAt')]
    public function testUsesVariables(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        LearnerFile::assertUsesVariable($source, '$title', self::FILE);
        LearnerFile::assertUsesVariable($source, '$content', self::FILE);
        LearnerFile::assertUsesVariable($source, '$createdAt', self::FILE);
    }

    #[TestDox('Latihan 3: setiap nilai ditulis sekali saja, di variabelnya')]
    public function testValuesWrittenOnce(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 3');
        $reason = 'Nilai ditulis sekali di variabel, lalu dicetak lewat interpolasi.';

        LearnerFile::assertTextAppearsOnce($source, 'Belajar variabel', $reason);
        LearnerFile::assertTextAppearsOnce($source, 'Hari ini saya belajar echo dan variabel.', $reason);
        LearnerFile::assertTextAppearsOnce($source, '2026-09-24', $reason);
    }

    #[TestDox('Latihan 3: mencetak pratinjau jurnal persis sesuai kontrak')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "Judul: Belajar variabel\n"
            . "Isi: Hari ini saya belajar echo dan variabel.\n"
            . "(Belajar variabel, 2026-09-24)\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 3.',
        );
    }
}
