<?php

namespace Calvert\DndErrors\Tests\Archetype;

use Calvert\DndErrors\Archetype\Wizard;
use Calvert\DndErrors\ErrorContext;
use PHPUnit\Framework\TestCase;

final class WizardTest extends TestCase
{
    private Wizard $wizard;

    protected function setUp(): void
    {
        $this->wizard = new Wizard();
    }

    public function testGetSlugReturnsWizard(): void
    {
        $this->assertSame('wizard', $this->wizard->getSlug());
    }

    public function testRenderContainsMessage(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Arcane failure',
            file: '/spellbook/incantation.php',
            line: 7
        );

        $output = $this->wizard->render($context);

        $this->assertStringContainsString('Arcane failure', $output);
        $this->assertStringContainsString('/spellbook/incantation.php:7', $output);
    }

    public function testRenderContainsWizardEmoji(): void
    {
        $context = new ErrorContext(
            type: 'Error',
            message: 'Test error',
        );

        $output = $this->wizard->render($context);

        // Should contain wizard emoji 🧙
        $this->assertStringContainsString('🧙', $output);
    }
}

