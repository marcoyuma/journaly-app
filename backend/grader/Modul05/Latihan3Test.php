<?php

declare(strict_types=1);

namespace Grader\Modul05;

use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan3Test extends TestCase
{
    private const FILE = 'playground/05-latihan-3.php';

    #[TestDox('Latihan 3: /search menjawab 200 dengan kata kuncinya, atau "(semua)" tanpa ?q=')]
    public function testSearch(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $cases = [
            '/search?q=php' => "Hasil pencarian: php\n",
            '/search?q=belajar%20sql' => "Hasil pencarian: belajar sql\n",
            '/search' => "Hasil pencarian: (semua)\n",
            '/search?page=2' => "Hasil pencarian: (semua)\n",
        ];

        $server = Server::start(self::FILE);

        try {
            foreach ($cases as $path => $expected) {
                $response = $server->request('GET', $path);

                $response->assertNoErrors(self::FILE);
                $response->assertStatus(200);
                $response->assertBody($expected);
            }
        } finally {
            $server->stop();
        }
    }

    #[TestDox('Latihan 3: path lain menjawab 404 "Endpoint tidak ditemukan."')]
    public function testOtherPaths(): void
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $server = Server::start(self::FILE);

        try {
            foreach (['/', '/journals?q=php', '/search/extra'] as $path) {
                $response = $server->request('GET', $path);

                $response->assertNoErrors(self::FILE);
                $response->assertStatus(404);
                $response->assertBody("Endpoint tidak ditemukan.\n");
            }
        } finally {
            $server->stop();
        }
    }
}
