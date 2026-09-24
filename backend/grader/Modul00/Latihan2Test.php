<?php

declare(strict_types=1);

namespace Grader\Modul00;

use Grader\Support\Git;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class Latihan2Test extends TestCase
{
    #[TestDox('Latihan 2: composer.json dan composer.lock sudah ter-commit tanpa perubahan tertunda')]
    public function testComposerFilesCommitted(): void
    {
        foreach (['backend/composer.json', 'backend/composer.lock'] as $file) {
            $this->assertTrue(
                Git::isTracked($file),
                "{$file} belum ter-commit. Ikuti langkah Konsep 4 (git add lalu git commit).",
            );
            $this->assertFalse(
                Git::hasUncommittedChanges($file),
                "{$file} punya perubahan yang belum di-commit. Cek dengan: git status",
            );
        }
    }

    #[TestDox('Latihan 2: Anda kembali di main dan branch feat/00-persiapan sudah di-merge lalu dihapus')]
    public function testBranchMergedAndDeleted(): void
    {
        $this->assertSame(
            'main',
            Git::currentBranch(),
            'Anda belum kembali ke main. Jalankan: git switch main, lalu git merge feat/00-persiapan.',
        );
        $this->assertFalse(
            Git::branchExists('feat/00-persiapan'),
            'Branch feat/00-persiapan masih ada. Setelah merge, hapus dengan: git branch -d feat/00-persiapan',
        );
    }
}
