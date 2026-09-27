<?php

function greeting(?string $username): string
{
    if ($username === null) {
        return "Silahkan login terlebih dahulu";
    } else {
        return "Halo, {$username}!";
    };
};

echo greeting("sari");
