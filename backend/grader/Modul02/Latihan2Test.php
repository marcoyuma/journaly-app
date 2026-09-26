<?php

declare(strict_types=1);

namespace Grader\Modul02;

use Grader\Support\Cli;
use Grader\Support\LearnerFile;
use Grader\Support\LearnerFunction;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    private const FILE = 'playground/02-latihan-2.php';

    #[TestDox('Latihan 2: greeting(null) meminta login')]
    public function testNull(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $this->assertSame(
            'Silakan login terlebih dahulu.',
            LearnerFunction::returnValue(self::FILE, 'greeting', [null]),
            'greeting(null) belum mengembalikan string yang diminta kontrak.',
        );
    }

    #[TestDox('Latihan 2: greeting dengan username menyapa username itu, termasuk string kosong')]
    public function testUsername(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $this->assertSame(
            'Halo, budi!',
            LearnerFunction::returnValue(self::FILE, 'greeting', ['budi']),
            'greeting(\'budi\') belum mengembalikan "Halo, budi!". Pakai parameter $username.',
        );
        $this->assertSame(
            'Halo, !',
            LearnerFunction::returnValue(self::FILE, 'greeting', ['']),
            'greeting(\'\') seharusnya "Halo, !". Kontrak: hanya null yang berarti belum login. Bandingkan dengan === null.',
        );
    }

    #[TestDox('Latihan 2: mencetak sapaan untuk null dan untuk sari')]
    public function testOutput(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $result = Cli::run(self::FILE);

        LearnerFile::assertNoWarnings($result, self::FILE);
        $this->assertSame(
            "Silakan login terlebih dahulu.\nHalo, sari!\n",
            $result->stdout,
            'Output belum persis sama dengan kontrak Latihan 2.',
        );
    }
}
