<?php

namespace Calvert\DndErrors\Archetype;

use Calvert\DndErrors\ErrorContext;

final class Ranger implements ArchetypeInterface
{
    public function getSlug(): string
    {
        return 'ranger';
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
            '🏹 The ranger spots trouble on the trail — something has broken. 🏹',
            '🏹 Fresh tracks: an error has passed through here. 🏹',
            '🏹 The ranger raises a hand — silence. The stack is disturbed. 🏹',
            '🏹 A hidden snare has been triggered in the codepath. 🏹',
            '🏹 The ranger draws an arrow: the bug is close. 🏹',
        );

        if ($context->type === 'fatal') {
            $headers[] = '🏹 Catastrophe in the wilds — the trail ends here. 🏹';
            $headers[] = '🏹 The ranger hears a crack… and the whole path collapses. Fatal. 🏹';
            $headers[] = '🏹 No safe route remains. The process has fallen. 🏹';
        }

        return $headers[random_int(0, count($headers) - 1)];
    }

    private function pickFlavourLine(ErrorContext $context): string
    {
        $lines = array(
            'The ranger kneels, studying the signs with a practiced eye.',
            'The ranger follows the codepath step by step, never rushing.',
            'The ranger marks the spot: “This is where it went wrong.”',
            'The ranger surveys the surroundings: “Something doesn\'t belong here.”',
            'The ranger listens for echoes in the stack, tracking the source.',
            'The ranger checks their map and compass. The trail should not have led here.',
            'The ranger mutters: “We\'ve been here before… and it never ends well.”',
        );

        if (stripos($context->message, 'undefined') !== false) {
            $lines[] = 'The ranger squints: “No footprints. That name was never here.”';
            $lines[] = 'The ranger taps the ground: “Undefined. You\'re tracking a ghost.”';
            $lines[] = 'The ranger warns: “You called for something that does not exist in these woods.”';
        }

        if (stripos($context->message, 'null') !== false) {
            $lines[] = 'The ranger finds an empty campsite: “Null. Nobody\'s home.”';
            $lines[] = 'The ranger shakes their head: “You reached for a value and grabbed air.”';
            $lines[] = 'The ranger says quietly: “The trail leads into the void.”';
        }

        if (stripos($context->message, 'argument') !== false || stripos($context->message, 'type') !== false) {
            $lines[] = 'The ranger checks the trail markers: “Wrong supplies for this route. Type mismatch.”';
            $lines[] = 'The ranger grunts: “You brought the wrong gear. The function won\'t accept it.”';
            $lines[] = 'The ranger points to the map: “Caller and callee disagree on the terrain.”';
        }

        if (stripos($context->message, 'parse') !== false || stripos($context->message, 'syntax') !== false) {
            $lines[] = 'The ranger sees broken branches: “Syntax is off. The path can\'t be read.”';
            $lines[] = 'The ranger winces: “The trail markers are scrambled. Nothing parses cleanly.”';
            $lines[] = 'The ranger mutters: “Bad sign. The interpreter refuses to follow this route.”';
        }

        if (stripos($context->message, 'memory') !== false) {
            $lines[] = 'The ranger checks the pack: “We\'re out of rations. Memory is exhausted.”';
            $lines[] = 'The ranger says: “Too much weight. The system can\'t carry any more.”';
            $lines[] = 'The ranger calls it: “We need to lighten the load, or we won\'t make it.”';
        }

        if ($context->type === 'fatal') {
            $lines[] = 'The ranger lowers their bow: “No healing salve for this. The path is dead.”';
            $lines[] = 'The ranger looks away: “The trail collapses behind us. Shutdown has begun.”';
            $lines[] = 'The ranger whispers: “We can\'t track what no longer runs.”';
        }

        return $lines[random_int(0, count($lines) - 1)];
    }
}
