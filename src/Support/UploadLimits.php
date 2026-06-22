<?php

declare(strict_types=1);

namespace CarePassport\Support;

final class UploadLimits
{
    public static function effectiveFileBytes(int $configuredMaxBytes): int
    {
        $limits = array_filter([
            $configuredMaxBytes,
            self::iniBytes('upload_max_filesize'),
            self::iniBytes('post_max_size'),
        ], static fn (int $bytes): bool => $bytes > 0);

        return $limits !== [] ? min($limits) : $configuredMaxBytes;
    }

    public static function iniBytes(string $key): int
    {
        $value = ini_get($key);

        if ($value === false) {
            return 0;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) ($number * 1024 * 1024 * 1024),
            'm' => (int) ($number * 1024 * 1024),
            'k' => (int) ($number * 1024),
            default => (int) $number,
        };
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            $mb = $bytes / (1024 * 1024);

            return rtrim(rtrim(number_format($mb, 1), '0'), '.') . ' MB';
        }

        if ($bytes >= 1024) {
            $kb = $bytes / 1024;

            return rtrim(rtrim(number_format($kb, 1), '0'), '.') . ' KB';
        }

        return $bytes . ' bytes';
    }
}
