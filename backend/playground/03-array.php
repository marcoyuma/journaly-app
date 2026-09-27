<?php

declare(strict_types=1);

// 1. A. list: values in order, numbered from 0
$titles = ["Hari pertama", "Belajar array"];
$titles[] = "Libur panjang";
echo "{$titles[2]}" . PHP_EOL;
var_dump($titles);


// 2. an associative array: each value has a name (key)
$journal = ["id" => 1, "title" => "Hari pertama"];
$journal['content'] = "Mulai menulis";
echo "#{$journal['id']}" . PHP_EOL;
var_dump($journal);


// 3 foreach
foreach ($titles as $index => $value) {
    # code...
    echo "-{$index}\n";
}
foreach ($journal as $key => $value) {
    # code...
    echo "-{$key}\n";
}


// 4. A function that takes a list of journals and returns a new list
function titlesOf(array $journals): array
{
    $result = [];
    foreach ($journals as $journal) {
        # code...
        $result[] = $journal['title'];
    }
    return $result;
}

$new = titlesOf([$journal, $journal]);
var_dump($new);

// 5. json_encode turns an array into json text
echo json_encode($titles);
echo json_encode(['key' => 'vakue']);
