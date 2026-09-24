<?php

declare(strict_types=1);

// Grader bootstrap: loads PHPUnit (via Composer) and the grader helpers.
// Written by the mentor. Learners run it with `composer grade NN`; they do not edit it.

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/Support/Paths.php';
require __DIR__ . '/Support/CliResult.php';
require __DIR__ . '/Support/Cli.php';
require __DIR__ . '/Support/LearnerFile.php';
require __DIR__ . '/Support/Git.php';
