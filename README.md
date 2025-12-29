# PHP D&D Errors

Turn PHP errors into D&D encounter messages.

## Installation

```bash
composer require calvert/php-dnd-errors
```

## Usage

Simply boot the error handler in your application:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Calvert\DndErrors\DndErrors;

DndErrors::boot([
    'archetype' => 'wizard', // 'paladin', 'ranger', 'wizard', or 'random'
    'exit' => true, // default: true
]);
```

## Options

- `archetype`: `'paladin'`, `'ranger'`, `'wizard'`, or `'random'` (default: `'random'`)
- `exit`: `true` or `false` - Whether to exit after rendering uncaught errors (default: `true`)

## Features

- Captures uncaught exceptions
- Converts PHP errors to exceptions
- Handles fatal errors
- Themed error messages with D&D archetype personality
- Context-aware flavor text based on error types

## Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Calvert\DndErrors\DndErrors;

DndErrors::boot(['archetype' => 'paladin']);

// Trigger an error
$array = null;
count($array);
```

## Development

This project uses PHP CodeSniffer with PSR-12 standard for code quality.

```bash
# Check code style
composer check

# Auto-fix code style issues
composer fix

# Or use vendor binaries directly
vendor/bin/phpcs --standard=PSR12 src/
vendor/bin/phpcbf --standard=PSR12 src/
```

## License

MIT
