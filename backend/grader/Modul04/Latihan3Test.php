<?php

declare(strict_types=1);

namespace Grader\Modul04;

use Grader\Support\HttpResponse;
use Grader\Support\LearnerFile;
use Grader\Support\Server;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan3Test extends TestCase
{
    private const FILE = 'playground/04-latihan-3.php';

    private static function maintenanceResponse(): HttpResponse
    {
        LearnerFile::assertFilled(self::FILE, 'Latihan 3');

        $server = Server::start(self::FILE);

        try {
            $response = $server->request('GET', '/journals');
        } finally {
            $server->stop();
        }

        $response->assertNoErrors(self::FILE);

        return $response;
    }

    #[TestDox('Latihan 3: status 503')]
    public function testStatus(): void
    {
        self::maintenanceResponse()->assertStatus(503);
    }

    #[TestDox('Latihan 3: header Content-Type dan Retry-After persis sesuai kontrak')]
    public function testHeaders(): void
    {
        $response = self::maintenanceResponse();

        $this->assertSame(
            'text/plain; charset=UTF-8',
            $response->header('Content-Type'),
            'Header Content-Type belum persis "text/plain; charset=UTF-8".',
        );
        $this->assertSame(
            '120',
            $response->header('Retry-After'),
            'Header Retry-After belum ada atau nilainya bukan 120.',
        );
    }

    #[TestDox('Latihan 3: body pesan perawatan persis sesuai kontrak')]
    public function testBody(): void
    {
        self::maintenanceResponse()->assertBody("Journaly sedang perawatan. Coba lagi nanti.\n");
    }
}
