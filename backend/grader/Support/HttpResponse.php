<?php

declare(strict_types=1);

namespace Grader\Support;

use PHPUnit\Framework\Assert;

final class HttpResponse
{
    /** @param array<string, string> $headers Lowercase header name => value. */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly int $status,
        public readonly array $headers,
        public readonly string $body,
        public readonly string $errors,
    ) {
    }

    public function header(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    /** Fails if PHP logged a warning or error while handling this request. */
    public function assertNoErrors(string $relativePath): void
    {
        if ($this->errors !== '') {
            Assert::fail(
                "PHP menampilkan warning atau error saat {$relativePath} menangani {$this->method} {$this->path}:\n"
                . $this->errors,
            );
        }
    }

    public function assertStatus(int $expected): void
    {
        Assert::assertSame(
            $expected,
            $this->status,
            "Status code untuk {$this->method} {$this->path} seharusnya {$expected}, tapi {$this->status}.",
        );
    }

    public function assertBody(string $expected): void
    {
        Assert::assertSame(
            $expected,
            $this->body,
            "Body response {$this->method} {$this->path} belum persis sama dengan kontrak.",
        );
    }
}
