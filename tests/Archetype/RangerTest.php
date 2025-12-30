<?php

namespace Calvert\DndErrors\Tests\Archetype;

use Calvert\DndErrors\Archetype\Ranger;
use Calvert\DndErrors\ErrorContext;
use PHPUnit\Framework\TestCase;

final class RangerTest extends TestCase
{
    private Ranger $ranger;

    protected function setUp(): void
    {
        $this->ranger = new Ranger();
    }

    public function testGetSlugReturnsRanger(): void
    {
        $this->assertSame('ranger', $this->ranger->getSlug());
    }

    public function testRenderContainsMessage(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Trail lost',
            file: '/forest/path.php',
            line: 15
        );

        $output = $this->ranger->render($context);

        $this->assertStringContainsString('Trail lost', $output);
        $this->assertStringContainsString('/forest/path.php:15', $output);
    }

    public function testRenderContainsRangerEmoji(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error',
        );

        $output = $this->ranger->render($context);

        // Should contain ranger emoji 🏹
        $this->assertStringContainsString('🏹', $output);
    }
}

