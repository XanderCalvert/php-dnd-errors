<?php

namespace Calvert\DndErrors\Tests\Archetype;

use Calvert\DndErrors\Archetype\Paladin;
use Calvert\DndErrors\ErrorContext;
use PHPUnit\Framework\TestCase;

final class PaladinTest extends TestCase
{
    private Paladin $paladin;

    protected function setUp(): void
    {
        $this->paladin = new Paladin();
    }

    public function testGetSlugReturnsPaladin(): void
    {
        $this->assertSame('paladin', $this->paladin->getSlug());
    }

    public function testRenderContainsMessage(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error message',
            file: '/path/to/file.php',
            line: 42
        );

        $output = $this->paladin->render($context);

        $this->assertStringContainsString('Test error message', $output);
        $this->assertStringContainsString('/path/to/file.php:42', $output);
    }

    public function testRenderContainsPaladinEmoji(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error',
        );

        $output = $this->paladin->render($context);

        // Should contain paladin emoji (✨ or ⚔️)
        $this->assertTrue(
            str_contains($output, '✨') || str_contains($output, '⚔️'),
            'Output should contain paladin emoji'
        );
    }

    public function testRenderIncludesLocationWhenProvided(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error',
            file: 'test.php',
            line: 10
        );

        $output = $this->paladin->render($context);

        $this->assertStringContainsString('test.php:10', $output);
    }

    public function testRenderShowsUnknownLocationWhenFileOrLineMissing(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error',
        );

        $output = $this->paladin->render($context);

        $this->assertStringContainsString('Unknown location', $output);
    }

    public function testRenderForFatalError(): void
    {
        $context = new ErrorContext(
            type: 'fatal',
            message: 'Fatal error occurred',
        );

        $output = $this->paladin->render($context);

        // Fatal errors should have special headers
        $this->assertStringContainsString('Fatal error occurred', $output);
    }
}

