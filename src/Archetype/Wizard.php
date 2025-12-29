<?php

namespace Calvert\DndErrors\Archetype;

use Calvert\DndErrors\ErrorContext;

final class Wizard implements ArchetypeInterface
{
    public function getSlug(): string
    {
        return 'wizard';
    }

    public function render(ErrorContext $context): string
    {
        $location = 'Unknown location';
        if (is_string($context->file) && is_int($context->line)) {
            $location = $context->file . ':' . $context->line;
        }

        $header = $this->pickHeader($context);
        $flavour = $this->pickFlavourLine($context);

        $body = array(
            $header,
            '',
            $flavour,
            '',
            'Message: ' . $context->message,
            'Location: ' . $location,
        );

        return implode("\n", $body) . "\n";
    }

    private function pickHeader(ErrorContext $context): string
    {
        $headers = array(
            '🧙 Arcane anomaly detected — the spell has misfired. 🧙',
            '🧙 The weave buckles under malformed incantation. 🧙',
            '🧙 A rune collapses mid-cast. The wizard demands clarity. 🧙',
            '🧙 The wizard consults the spellbook: this behaviour is… unexpected. 🧙',
            '🧙 Detect Error reveals a disturbance in the arcane contract. 🧙',
            '🧙 The wizard attempts to stabilise the ritual… and fails. 🧙',
        );

        if ($context->type === 'fatal') {
            $headers[] = '🧙 Catastrophic backlash — the ritual has torn reality. 🧙';
            $headers[] = '🧙 Planar breach: fatal error. There will be no counterspell. 🧙';
            $headers[] = '🧙 The wizard\'s ward shatters. Shutdown is inevitable. 🧙';
        }

        return $headers[random_int(0, count($headers) - 1)];
    }

    private function pickFlavourLine(ErrorContext $context): string
    {
        $lines = array(
            'The wizard scratches their beard, perplexed.',
            'The wizard mutters ancient arcana to no avail.',
            'The wizard\'s spellbook offers no guidance for this miscast.',
            'The wizard attempts a Counterspell… but the error persists.',
            'The wizard rolls a natural 1 on their arcana check.',
            'The wizard peers into a crystal ball and sees only stack traces.',
            'The wizard frantically searches scrolls for a missing rune.',
            'The wizard\'s familiar tilts its head, equally confused.',
            'The wizard realises the spell components are insufficient.',
            'The wizard checks their spell slots… all expended on debugging.',
            'The wizard casts Identify. The diagnosis is… unfortunate.',
            'The wizard\'s staff crackles with energy, then fizzles into silence.',
        );

        if (stripos($context->message, 'undefined') !== false) {
            $lines[] = 'The wizard searches their spellbook… the rune is not recorded.';
            $lines[] = 'The wizard attempts to invoke a name that has never been bound.';
            $lines[] = 'The wizard points: “This spell does not exist. Define it, or cease invoking it.”';
        }

        if (stripos($context->message, 'null') !== false) {
            $lines[] = 'The wizard reaches into their component pouch… it yields only nothingness.';
            $lines[] = 'The wizard tries to cast with no target. The void consumes the spell.';
            $lines[] = 'The wizard\'s arcane focus points to absence. A troubling sign.';
        }

        if (stripos($context->message, 'argument') !== false || stripos($context->message, 'type') !== false) {
            $lines[] = 'The wizard taps the parchment: “The contract is explicit. Honour the expected type.”';
            $lines[] = 'The wizard sighs: “You promised an array. You delivered something else.”';
            $lines[] = 'The wizard revises the ritual: “Caller and callee must agree on the sigils.”';
        }

        if ($context->type === 'fatal') {
            $lines[] = 'The wizard is reduced to 0 hit points. The ritual ends here.';
            $lines[] = 'A contingency triggers… but too late. The plane is already collapsing.';
            $lines[] = 'The wizard attempts Revivify. Even magic cannot restore a dead process.';
            $lines[] = 'The wizard reaches for a counterspell, but shutdown has already begun.';
        }

        if (stripos($context->message, 'parse') !== false || stripos($context->message, 'syntax') !== false) {
            $lines[] = 'The incantation is malformed. The weave rejects it immediately.';
            $lines[] = 'A rune is misplaced. The spell collapses before it can form.';
            $lines[] = 'The wizard grimaces: “Your syntax is wrong. Reality will not parse it.”';
        }

        if (stripos($context->message, 'memory') !== false) {
            $lines[] = 'The wizard has run out of spell slots. Memory is exhausted.';
            $lines[] = 'The spellbook is too full—no room remains for further magic.';
            $lines[] = 'The wizard\'s mind cannot contain more runes. The limit has been reached.';
        }

        return $lines[random_int(0, count($lines) - 1)];
    }
}
