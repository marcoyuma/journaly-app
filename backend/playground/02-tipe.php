<?php


declare(strict_types=1);


// compare value and type
var_dump(3 === 3);
var_dump(3 === "3");

// 3. if/else
$title = '';
if ($title === '') {
    echo "tanpa judul\n";
} else {
    echo "{$title}\n";
}



// 4. A typed function: a string goes in, a bool comes out
function isEmptyTitle(string $title): bool
{
    return $title === '';
}

var_dump(isEmptyTitle(''));
var_dump(isEmptyTitle('Hari pertama'));



// 5. ?string accepts a string or null; parameters are separated by commas
function authorLabel(?string $author, string $fallback): string
{
    if ($author === null) {
        return $fallback;
    }

    return $author;
}

$label = authorLabel(null, 'Anonim');
echo "{$label}\n";
$label = authorLabel('Sari', 'Anonim');
echo "{$label}\n";



// 6. Wrong type on purpose: an int where ?string is expected
$label = authorLabel(2026, 'Anonim');
echo "{$label}\n";
