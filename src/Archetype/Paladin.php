<?php

namespace Calvert\DndErrors\Archetype;

use Calvert\DndErrors\ErrorContext;

final class Paladin implements ArchetypeInterface
{
    public function getSlug(): string
    {
        return 'paladin';
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
            "✨ A PALADIN detects a breach of oath! ✨",
            "✨ DIVINE WARNING: The sacred contract has been violated. ✨",
            "✨ The PALADIN raises a shield—an error approaches. ✨",
            "✨ Holy light reveals an unclean exception. ✨",
            "✨ The PALADIN invokes Divine Sense… and finds corruption in the stack. ✨",
        );

        if ($context->type === 'fatal') {
            $headers[] = "⚔️ CATASTROPHE: The oath is broken and the realm crashes. ⚔️";
            $headers[] = "⚔️ DIVINE JUDGEMENT: Fatal error—no saving throw granted. ⚔️";
        }

        return $headers[random_int(0, count($headers) - 1)];
    }

    private function pickFlavourLine(ErrorContext $context): string
    {
        $lines = array(
            'The paladin speaks calmly: “We will mend this, but we must face the truth.”',
            'The paladin consults the code as though it were scripture.',
            'The paladin declares: “Order must be restored.”',
            'The paladin takes a steady breath and begins the rite of debugging.',
            'The paladin points at the offending line: “Here lies the sin.”',
            'The paladin intones: “Let the light of clarity illuminate this error.”',
            'The paladin tightens their gauntlet: “We do not ship broken oaths.”',
        );

        if (stripos($context->message, 'undefined') !== false) {
            $lines[] = 'The paladin declares: “You have invoked a name that does not exist.”';
            $lines[] = 'The paladin frowns: “This symbol has no covenant. Define it.”';
        }

        if (stripos($context->message, 'null') !== false) {
            $lines[] = 'The paladin warns: “You offered nothing, and expected something in return.”';
            $lines[] = 'The paladin sighs: “The void is not a valid offering.”';
        }

        if (stripos($context->message, 'argument') !== false || stripos($context->message, 'type') !== false) {
            $lines[] = 'The paladin recites: “The contract is clear. Honour the types you swore to uphold.”';
            $lines[] = 'The paladin judges: “An oath was made between function and caller. It has been broken.”';
        }

        if ($context->type === 'fatal') {
            $lines[] = 'The paladin whispers: “No healing spell can undo what has already collapsed.”';
            $lines[] = 'The paladin attempts Lay on Hands… but the process has already left this plane.';
            $lines[] = 'The paladin calls for aid, but the realm is already in shutdown.';
        }

        if (stripos($context->message, 'parse') !== false || stripos($context->message, 'syntax') !== false) {
            $lines[] = 'The paladin condemns the incantation: “Your syntax is heresy. The interpreter rejects it.”';
            $lines[] = 'The paladin corrects the scroll: “These runes are misplaced. Set them right.”';
        }

        if (stripos($context->message, 'memory') !== false) {
            $lines[] = 'The paladin observes: “Your resources are exhausted. Retreat, refactor, return.”';
            $lines[] = 'The paladin orders a strategic withdrawal: “Memory is depleted. We cannot sustain this fight.”';
        }

        return $lines[random_int(0, count($lines) - 1)];
    }
}
