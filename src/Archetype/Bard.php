<?php

namespace Calvert\DndErrors\Archetype;

use Calvert\DndErrors\ErrorContext;

final class Bard implements ArchetypeInterface
{
    public function getSlug(): string
    {
        return 'bard';
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
            "🎻 A BARD hears a discordant note in the runtime. 🎻",
            "🎭 TRAGICOMEDY: An exception takes centre stage. 🎭",
            "🎶 The BARD strikes a chord… and the stack trace harmonises back. 🎶",
            "🍷 TALE OF WOES: The script falters mid-verse. 🍷",
            "🪕 The BARD tunes the lute—something’s off in the code. 🪕",
        );

        if ($context->type === 'fatal') {
            $headers[] = "💀 FINAL VERSE: Fatal error—curtain down, no encore. 💀";
            $headers[] = "🩸 TRAGEDY: The runtime collapses to thunderous silence. 🩸";
        }

        return $headers[random_int(0, count($headers) - 1)];
    }

    private function pickFlavourLine(ErrorContext $context): string
    {
        $lines = array(
            'The bard winks: “Every bug is a plot twist, darling.”',
            'The bard sighs dramatically: “Alas, our hero has failed their saving throw.”',
            'The bard narrates: “And lo—upon line and file, betrayal was revealed.”',
            'The bard strums softly: “We can fix this… with a little rhythm and discipline.”',
            'The bard raises a glass: “To the brave debugger, and the doomed deploy.”',
            'The bard declares: “Let us rewrite this scene before the audience notices.”',
        );

        if (stripos($context->message, 'undefined') !== false) {
            $lines[] = 'The bard sings: “A name without a chorus… undefined, unheard, unseen.”';
            $lines[] = 'The bard mutters: “You introduced a character that never appears in Act One.”';
        }

        if (stripos($context->message, 'null') !== false) {
            $lines[] = 'The bard gasps: “You handed the stage… nothingness.”';
            $lines[] = 'The bard whispers: “Null entered the tavern, and the whole story fell quiet.”';
        }

        if (stripos($context->message, 'argument') !== false || stripos($context->message, 'type') !== false) {
            $lines[] = 'The bard scolds: “Your cast is mismatched—wrong role, wrong type, wrong timing.”';
            $lines[] = 'The bard sighs: “That argument simply doesn’t fit the metre.”';
        }

        if ($context->type === 'fatal') {
            $lines[] = 'The bard bows: “No rewrite can save a stage that has already burned.”';
            $lines[] = 'The bard murmurs: “The final line was spoken… and the process exited.”';
            $lines[] = 'The bard lowers their voice: “Silence. The realm has crashed.”';
        }

        if (stripos($context->message, 'parse') !== false || stripos($context->message, 'syntax') !== false) {
            $lines[] = 'The bard winces: “Those lines don’t scan. The syntax breaks the song.”';
            $lines[] = 'The bard taps the parchment: “Your verse is malformed. The interpreter refuses to read it.”';
        }

        if (stripos($context->message, 'memory') !== false) {
            $lines[] = 'The bard warns: “We’ve spent the last of our inspiration… memory is gone.”';
            $lines[] = 'The bard declares: “Too many verses, not enough breath—resources exhausted.”';
        }

        return $lines[random_int(0, count($lines) - 1)];
    }
}
