<?php

declare(strict_types=1);

namespace Grader\Modul03;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use Grader\Support\LearnerFunction;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan3Test extends TestCase
{
    private const FILE = 'playground/03-latihan-3.php';

    #[TestDox('Latihan 3: publicJournals membuang user_id dan menjaga urutan id, title, content')]
    public function testRemovesUserId(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $input = [
            ['id' => 5, 'user_id' => 9, 'title' => 'Satu', 'content' => 'Isi satu.'],
            ['id' => 8, 'user_id' => 9, 'title' => 'Dua', 'content' => 'Isi dua.'],
            ['id' => 13, 'user_id' => 4, 'title' => 'Tiga', 'content' => 'Isi tiga.'],
        ];

        $this->assertSame(
            [
                ['id' => 5, 'title' => 'Satu', 'content' => 'Isi satu.'],
                ['id' => 8, 'title' => 'Dua', 'content' => 'Isi dua.'],
                ['id' => 13, 'title' => 'Tiga', 'content' => 'Isi tiga.'],
            ],
            LearnerFunction::returnValue(self::FILE, 'publicJournals', [$input]),
            'publicJournals belum mengembalikan list yang diminta kontrak: setiap jurnal hanya berisi id, title, '
            . 'dan content (dalam urutan itu), tanpa user_id. Grader memanggilnya dengan data lain, jadi olah '
            . 'parameter $journals dengan foreach.',
        );
    }

    #[TestDox('Latihan 3: publicJournals dengan list kosong mengembalikan list kosong')]
    public function testEmptyList(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $this->assertSame(
            [],
            LearnerFunction::returnValue(self::FILE, 'publicJournals', [[]]),
            'publicJournals([]) seharusnya mengembalikan [].',
        );
    }

    #[TestDox('Latihan 3: mencetak JSON {"data": [...]} persis sesuai kontrak')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            '{"data":[{"id":1,"title":"Hari pertama","content":"Mulai menulis."},'
            . '{"id":2,"title":"Belajar array","content":"Array menyimpan banyak nilai."}]}' . "\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 3.',
        );
    }
}
