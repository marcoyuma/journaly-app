<?php

declare(strict_types=1);

$journal = ['id' => 7, 'title' => 'Belajar array', 'content' => 'Array menyimpan banyak nilai'];
$journal[] = ['created_at' => '2026-09-25'];
// echo "#{$journal['id']} {$journal['title']}" . PHP_EOL;
// echo "{$journal['content']}\n";

function publicJournals(array $journals): array
{
    $result = [];
    foreach ($journals as $journal) {
        $result[] = [
            "id" => $journal['id'],
            "title" => $journal['title'],
            "content" => $journal['content'],
        ];
    }

    return $result;
}

$journals = [
    ['id' => 7, 'title' => 'Belajar array', 'content' => 'Array menyimpan banyak nilai'],
    ['id' => 7, 'title' => 'Belajar array', 'content' => 'Array menyimpan banyak nilai'],
];

$a = json_encode(["data" => publicJournals($journals)]);
echo ($a);
