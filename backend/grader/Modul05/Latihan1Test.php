<?php

declare(strict_types=1);

namespace Grader\Modul05;

use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan1Test extends TestCase
{
    private const FILE = 'playground/05-latihan-1.php';

    #[TestDox('Latihan 1: mencetak method dan path (tanpa query string) dari setiap request')]
    public function testMethodAndPath(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 1');

        $cases = [
            ['GET', '/journals?page=2', "Method: GET\nPath: /journals\n"],
            ['POST', '/auth/login', "Method: POST\nPath: /auth/login\n"],
            ['DELETE', '/journals/5?confirm=1', "Method: DELETE\nPath: /journals/5\n"],
        ];

        $server = Server::start(self::FILE);

        try {
            foreach ($cases as [$method, $path, $expected]) {
                $response = $server->request($method, $path);

                $response->assertNoErrors(self::FILE);
                $response->assertStatus(200);
                $response->assertBody($expected);
            }
        } finally {
            $server->stop();
        }
    }
}
