<?php

declare(strict_types=1);

namespace Grader\Modul05;

use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    private const FILE = 'playground/05-latihan-2.php';

    #[TestDox('Latihan 2: menyapa nama dari query string ?name=')]
    public function testName(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $server = Server::start(self::FILE);

        try {
            foreach (['/?name=Sari' => "Halo, Sari!\n", '/?name=Budi%20Santoso' => "Halo, Budi Santoso!\n"] as $path => $expected) {
                $response = $server->request('GET', $path);

                $response->assertNoErrors(self::FILE);
                $response->assertBody($expected);
            }
        } finally {
            $server->stop();
        }
    }

    #[TestDox('Latihan 2: tanpa ?name= menyapa "tamu" tanpa warning')]
    public function testDefault(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $server = Server::start(self::FILE);

        try {
            foreach (['/', '/journals?page=2'] as $path) {
                $response = $server->request('GET', $path);

                $response->assertNoErrors(self::FILE);
                $response->assertBody("Halo, tamu!\n");
            }
        } finally {
            $server->stop();
        }
    }
}
