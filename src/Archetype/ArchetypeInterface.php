<?php

namespace Calvert\DndErrors\Archetype;

use Calvert\DndErrors\ErrorContext;

interface ArchetypeInterface
{
    public function getSlug(): string;

    public function render(ErrorContext $context): string;
}
