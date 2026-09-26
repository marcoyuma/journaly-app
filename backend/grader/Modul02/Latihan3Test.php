<?php

declare(strict_types=1);

namespace Grader\Modul02;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use Grader\Support\LearnerFunction;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan3Test extends TestCase
{
    private const FILE = 'playground/02-latihan-3.php';

    #[TestDox('Latihan 3: file diawali declare(strict_types=1)')]
    public function testStrictTypes(): void
    {
        $source = LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        LearnerFile::assertDeclaresStrictTypes($source, self::FILE);
    }

    #[TestDox('Latihan 3: canEdit mengembalikan bool sesuai kontrak')]
    public function testContract(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $cases = [
            [[7, null], false, 'pengguna yang belum login (null) tidak boleh mengubah'],
            [[7, 7], true, 'pemilik jurnal boleh mengubah'],
            [[7, 8], false, 'pengguna lain tidak boleh mengubah'],
        ];

        foreach ($cases as [$arguments, $expected, $reason]) {
            $this->assertSame(
                $expected,
                LearnerFunction::returnValue(self::FILE, 'canEdit', $arguments),
                "canEdit({$arguments[0]}, " . var_export($arguments[1], true) . ") salah: {$reason}. "
                . 'Nilai kembaliannya harus bool (true atau false), bukan string atau angka.',
            );
        }
    }

    #[TestDox('Latihan 3: parameter pertama bertipe int sehingga string memicu TypeError')]
    public function testTypeError(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        LearnerFunction::assertThrows(
            self::FILE,
            'canEdit',
            ['7', 7],
            'TypeError',
            'Tulis tipe int pada parameter $journalUserId.',
        );
    }

    #[TestDox('Latihan 3: mencetak tiga hasil canEdit dengan var_dump')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "bool(false)\nbool(true)\nbool(false)\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 3.',
        );
    }
}
