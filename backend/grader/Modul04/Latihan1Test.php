<?php

declare(strict_types=1);

namespace Grader\Modul04;

use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    private const FILE = 'playground/04-latihan-1.php';

    #[TestDox('Latihan 1: setiap request dijawab 200 dengan body sambutan')]
    public function testEveryRequest(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $server = Server::start(self::FILE);

        try {
            foreach ([['GET', '/'], ['GET', '/journals'], ['POST', '/auth/login']] as [$method, $path]) {
                $response = $server->request($method, $path);

                $response->assertNoErrors(self::FILE);
                $response->assertStatus(200);
                $response->assertBody("Selamat datang di Journaly API\n");
            }
        } finally {
            $server->stop();
        }
    }
}
