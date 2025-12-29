<?php

namespace Calvert\DndErrors;

final class ErrorContext
{
    public function __construct(
        public readonly string $type,
        public readonly string $message,
        public readonly ?string $file = null,
        public readonly ?int $line = null,
        public readonly array $trace = array(),
        public readonly ?string $phpVersion = null
    ) {
    }
}
