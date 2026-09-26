<?php

declare(strict_types=1);

namespace Grader\Modul04;

use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    private const FILE = 'playground/04-latihan-2.php';

    #[TestDox('Latihan 2: menjawab 401 dengan body "Silakan login terlebih dahulu."')]
    public function testUnauthorized(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 2');

        $server = Server::start(self::FILE);

        try {
            $response = $server->request('GET', '/auth/me');

            $response->assertNoErrors(self::FILE);
            $response->assertStatus(401);
            $response->assertBody("Silakan login terlebih dahulu.\n");
        } finally {
            $server->stop();
        }
    }
}
