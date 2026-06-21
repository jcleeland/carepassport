<?php

declare(strict_types=1);

namespace CarePassport\Support;

final class Env
{
    public static function load(string $path): void
    {
        if (! is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);

            if ($key === '') {
                continue;
            }

            $value = self::cleanValue(trim($value));

            if (getenv($key) !== false) {
                $_ENV[$key] = getenv($key);
                $_SERVER[$key] = getenv($key);
                continue;
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv($key . '=' . $value);
        }
    }

    private static function cleanValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $quote = $value[0];
        $last = $value[strlen($value) - 1];

        if (($quote === '"' || $quote === "'") && $last === $quote) {
            $value = substr($value, 1, -1);
        }

        return str_replace(['\n', '\r'], ["\n", "\r"], $value);
    }
}
