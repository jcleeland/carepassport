<?php

declare(strict_types=1);

namespace CarePassport\View;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $payload = []): string
    {
        $templatePath = base_path('views/' . $template . '.php');

        if (! is_file($templatePath)) {
            throw new \RuntimeException('View not found: ' . $template);
        }

        $view = $this;

        ob_start();
        extract($payload, EXTR_SKIP);
        require $templatePath;
        $content = (string) ob_get_clean();

        ob_start();
        require base_path('views/layout.php');

        return (string) ob_get_clean();
    }

    public function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
