<?php

namespace Calvert\DndErrors;

use Calvert\DndErrors\Archetype\ArchetypeInterface;
use Calvert\DndErrors\Archetype\Paladin;
use Calvert\DndErrors\Archetype\Wizard;
use Calvert\DndErrors\Archetype\Ranger;
use ErrorException;
use Throwable;

final class DndErrors
{
    private static bool $booted = false;

    /**
     * @param array $options {
     *     @type string $archetype 'paladin'|'ranger'|'wizard'|'random'
     *     @type bool   $exit Whether to exit after rendering uncaught errors. Default true.
     * }
     */
    public static function boot(array $options = array()): void
    {
        if (self::$booted) {
            return;
        }

        self::$booted = true;

        $archetypeSlug = isset($options['archetype']) ? (string) $options['archetype'] : 'random';
        $shouldExit  = array_key_exists('exit', $options) ? (bool) $options['exit'] : true;

        $archetype = self::resolveArchetype($archetypeSlug);

        set_exception_handler(function (Throwable $throwable) use ($archetype, $shouldExit): void {
            $context = self::contextFromThrowable($throwable);
            self::emit($archetype->render($context));

            if ($shouldExit) {
                exit(1);
            }
        });

        set_error_handler(function (
            int $severity,
            string $message,
            string $file,
            int $line
        ) use (
            $archetype,
            $shouldExit
): bool {
            // Respect @-suppression.
            if ((error_reporting() & $severity) === 0) {
                return false;
            }

            $exception = new ErrorException($message, 0, $severity, $file, $line);
            $context = self::contextFromThrowable($exception);
            self::emit($archetype->render($context));

            if ($shouldExit) {
                exit(1);
            }

            // We handled it.
            return true;
        });

        register_shutdown_function(function () use ($archetype, $shouldExit): void {
            $last = error_get_last();
            if (!is_array($last)) {
                return;
            }

            // Fatal-ish types.
            $fatalTypes = array(
                E_ERROR,
                E_PARSE,
                E_CORE_ERROR,
                E_COMPILE_ERROR,
                E_USER_ERROR,
            );

            if (!in_array((int) $last['type'], $fatalTypes, true)) {
                return;
            }

            $context = new ErrorContext(
                type: 'fatal',
                message: (string) $last['message'],
                file: isset($last['file']) ? (string) $last['file'] : null,
                line: isset($last['line']) ? (int) $last['line'] : null,
                trace: array(),
                phpVersion: PHP_VERSION
            );

            self::emit($archetype->render($context));

            if ($shouldExit) {
                // If we're here, PHP is already shutting down; exit is fine.
                exit(1);
            }
        });
    }

    private static function resolveArchetype(string $slug): ArchetypeInterface
    {
        $available = array(
            'paladin' => new Paladin(),
            'wizard' => new Wizard(),
            'ranger' => new Ranger(),
        );

        if ($slug === 'random') {
            $keys = array_keys($available);
            $chosen = $keys[random_int(0, count($keys) - 1)];

            return $available[$chosen];
        }

        if (isset($available[$slug])) {
            return $available[$slug];
        }

        // Fallback: keep it safe.
        return $available['wizard'];
    }

    private static function contextFromThrowable(Throwable $throwable): ErrorContext
    {
        return new ErrorContext(
            type: $throwable::class,
            message: $throwable->getMessage(),
            file: $throwable->getFile(),
            line: $throwable->getLine(),
            trace: $throwable->getTrace(),
            phpVersion: PHP_VERSION
        );
    }

    private static function emit(string $output): void
    {
        // For v0.1 keep it simple: plain text output.
        // In a later version, you can add HTML mode and WordPress wp_die integration.
        if (PHP_SAPI !== 'cli') {
            header('Content-Type: text/plain; charset=utf-8', true);
        }

        echo $output;
    }
}
