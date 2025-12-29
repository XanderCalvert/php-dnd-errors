<?php

namespace Calvert\DndErrors\Tests;

use Calvert\DndErrors\ErrorContext;
use PHPUnit\Framework\TestCase;

final class ErrorContextTest extends TestCase
{
    public function testCanCreateErrorContextWithAllProperties(): void
    {
        $context = new ErrorContext(
            type: 'RuntimeException',
            message: 'Test error message',
            file: '/path/to/file.php',
            line: 42,
            trace: array('trace' => 'data'),
            phpVersion: '8.1.0'
        );

        $this->assertSame('RuntimeException', $context->type);
        $this->assertSame('Test error message', $context->message);
        $this->assertSame('/path/to/file.php', $context->file);
        $this->assertSame(42, $context->line);
        $this->assertSame(array('trace' => 'data'), $context->trace);
        $this->assertSame('8.1.0', $context->phpVersion);
    }

    public function testCanCreateErrorContextWithMinimalProperties(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test message'
        );

        $this->assertSame('Error', $context->type);
        $this->assertSame('Test message', $context->message);
        $this->assertNull($context->file);
        $this->assertNull($context->line);
        $this->assertSame(array(), $context->trace);
        $this->assertNull($context->phpVersion);
    }
}

