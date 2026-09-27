<?php

declare(strict_types=1);

// TODO 1: write countLabel(int $count): string
function countLabel(int $count): string
{
    // 0 returns 'Belum ada jurnal', any other number returns "<count> jurnal"
    if ($count === 0) {
        return "Belum ada jurnal";
    }
    // TODO 2: print countLabel(0) followed by a new line
    else {
        return "{$count} jurnal";
    };
}

echo $jumlahJurnal = countLabel(0);
echo $jumlahJurnal = countLabel(3);
