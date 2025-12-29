<?php

require_once __DIR__ . '/vendor/autoload.php';

use Calvert\DndErrors\DndErrors;

DndErrors::boot(array(
    'archetype' => 'random', // Randomly picks between available archetypes
));

// Use Unix timestamp to cycle through different error types
$errorType = time() % 6;

switch ($errorType) {
    case 0:
        // Undefined variable
        echo $undefinedVariable;
        break;

    case 1:
        // Null pointer / count() on null (deprecated warning in PHP 8.1+)
        $array = null;
        count($array);
        break;

    case 2:
        // Call to undefined function
        undefinedFunctionCall();
        break;

    case 3:
        // Type error / wrong argument count
        substr('hello', 0, 5, 10); // substr doesn't accept 4 arguments
        break;

    case 4:
        // Array offset on null
        $data = null;
        $data['key'] = 'value';
        break;

    case 5:
        // Division by zero
        $result = 10 / 0;
        break;
}
