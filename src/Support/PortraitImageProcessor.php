<?php

declare(strict_types=1);

namespace CarePassport\Support;

final class PortraitImageProcessor
{
    /**
     * @param array{
     *     max_upload_bytes:int,
     *     processed_width:int,
     *     processed_height:int,
     *     storage_directory:string,
     *     allowed_extensions:list<string>,
     *     allowed_mime_types:list<string>
     * } $config
     */
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @param array<string, mixed> $file
     * @return array{original_path:string,processed_path:string}
     */
    public function storeUploadedPortrait(int $residentId, array $file): array
    {
        $this->validateUpload($file);

        $tmpName = (string) $file['tmp_name'];
        $mimeType = $this->mimeType($tmpName);
        $extension = $this->extension((string) $file['name'], $mimeType);
        $directory = $this->portraitDirectory($residentId);
        $token = bin2hex(random_bytes(16));
        $originalRelativePath = $directory . '/' . $token . '-original.' . $extension;
        $processedRelativePath = $directory . '/' . $token . '-portrait.jpg';
        $originalPath = base_path($originalRelativePath);
        $processedPath = base_path($processedRelativePath);

        $this->ensureDirectory(dirname($originalPath));

        if (! move_uploaded_file($tmpName, $originalPath)) {
            throw new \RuntimeException('The photo could not be saved. Please try again.');
        }

        try {
            $this->createProcessedPortrait($originalPath, $mimeType, $processedPath);
        } catch (\Throwable $exception) {
            @unlink($originalPath);
            @unlink($processedPath);

            throw $exception;
        }

        return [
            'original_path' => $originalRelativePath,
            'processed_path' => $processedRelativePath,
        ];
    }

    /**
     * @param array<string, mixed> $file
     */
    private function validateUpload(array $file): void
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            throw new \InvalidArgumentException('Choose a portrait photo to upload.');
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException('The photo upload did not complete. Please try again.');
        }

        $size = (int) ($file['size'] ?? 0);

        if ($size <= 0) {
            throw new \InvalidArgumentException('The selected file is empty.');
        }

        if ($size > (int) $this->config['max_upload_bytes']) {
            throw new \InvalidArgumentException('The photo must be 5 MB or smaller.');
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');

        if ($tmpName === '' || ! is_uploaded_file($tmpName)) {
            throw new \InvalidArgumentException('The uploaded photo could not be read.');
        }

        $mimeType = $this->mimeType($tmpName);

        if (! in_array($mimeType, $this->config['allowed_mime_types'], true)) {
            throw new \InvalidArgumentException('Upload a JPG, PNG or WebP image.');
        }

        $extension = $this->extension((string) ($file['name'] ?? ''), $mimeType);

        if (! in_array($extension, $this->config['allowed_extensions'], true)) {
            throw new \InvalidArgumentException('Upload a JPG, PNG or WebP image.');
        }

        if (getimagesize($tmpName) === false) {
            throw new \InvalidArgumentException('The selected file is not a readable image.');
        }
    }

    private function createProcessedPortrait(string $sourcePath, string $mimeType, string $destinationPath): void
    {
        $source = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default => false,
        };

        if (! $source instanceof \GdImage) {
            throw new \RuntimeException('The photo could not be processed.');
        }

        if ($mimeType === 'image/jpeg') {
            $source = $this->orientJpeg($sourcePath, $source);
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $targetWidth = (int) $this->config['processed_width'];
        $targetHeight = (int) $this->config['processed_height'];
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $sourceX = (int) floor(($sourceWidth - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) floor(($sourceHeight - $cropHeight) / 2);
        }

        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        if (! $target instanceof \GdImage) {
            imagedestroy($source);
            throw new \RuntimeException('The processed portrait could not be created.');
        }

        $white = imagecolorallocate($target, 255, 255, 255);
        imagefill($target, 0, 0, $white);
        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            $sourceX,
            $sourceY,
            $targetWidth,
            $targetHeight,
            $cropWidth,
            $cropHeight,
        );

        if (! imagejpeg($target, $destinationPath, 88)) {
            imagedestroy($source);
            imagedestroy($target);
            throw new \RuntimeException('The processed portrait could not be saved.');
        }

        imagedestroy($source);
        imagedestroy($target);
    }

    private function orientJpeg(string $path, \GdImage $image): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = is_array($exif) ? (int) ($exif['Orientation'] ?? 1) : 1;

        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        return $rotated instanceof \GdImage ? $rotated : $image;
    }

    private function mimeType(string $path): string
    {
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        return (string) $fileInfo->file($path);
    }

    private function extension(string $filename, string $mimeType): string
    {
        $extension = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));

        if ($extension === 'jpeg') {
            return 'jpg';
        }

        if ($extension !== '') {
            return $extension;
        }

        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => '',
        };
    }

    private function portraitDirectory(int $residentId): string
    {
        return trim((string) $this->config['storage_directory'], '/') . '/' . $residentId;
    }

    private function ensureDirectory(string $directory): void
    {
        if (is_dir($directory)) {
            @chmod($directory, 0775);
            return;
        }

        if (! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new \RuntimeException('The photo storage directory could not be created.');
        }

        @chmod($directory, 0775);
    }
}
