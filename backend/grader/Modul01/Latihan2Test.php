<?php

declare(strict_types=1);

namespace Grader\Modul01;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    private const FILE = 'playground/01-latihan-2.php';

    #[TestDox('Latihan 2: memakai variabel $title dan $author')]
    public function testUsesVariables(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        LearnerFile::assertUsesVariable($source, '$title', self::FILE);
        LearnerFile::assertUsesVariable($source, '$author', self::FILE);
    }

    #[TestDox('Latihan 2: mencetak judul dan penulis persis sesuai kontrak')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "Judul: Hari pertama belajar PHP\nPenulis: Sari\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 2.',
        );
    }
}
