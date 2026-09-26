<?php

declare(strict_types=1);

// Helper process for LearnerFunction: loads a learner's file (discarding what it prints),
// then calls one of its functions with the given arguments. Because this file declares
// strict_types, the call is checked strictly, exactly as in the learner's own strict file.
//
// Usage: php call-function.php <absolute file> <function> <serialized argument list>
// Prints one serialized array describing what happened.

[, $file, $function, $serializedArguments] = $argv;
$arguments = unserialize($serializedArguments, ['allowed_classes' => false]);

ob_start();

try {
    require $file;
} catch (Throwable $error) {
    ob_end_clean();
    echo serialize(['status' => 'load-error', 'class' => $error::class, 'message' => $error->getMessage()]);
    exit;
}

ob_end_clean();

if (!function_exists($function)) {
    echo serialize(['status' => 'missing']);
    exit;
}

try {
    $value = $function(...$arguments);
    echo serialize(['status' => 'returned', 'value' => $value]);
} catch (Throwable $error) {
    echo serialize(['status' => 'thrown', 'class' => $error::class, 'message' => $error->getMessage()]);
}
