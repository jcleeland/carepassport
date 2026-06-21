<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/bootstrap/app.php';
$app = $config['app'];

$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($app['name']) ?></title>
</head>
<body>
    <main style="font-family: system-ui, sans-serif; max-width: 760px; margin: 4rem auto; padding: 0 1rem; line-height: 1.5;">
        <h1><?= $escape($app['name']) ?></h1>
        <p>Foundation bootstrap is installed. Implement the MVP from the documentation in <code>docs/</code>.</p>
        <p>Environment: <code><?= $escape($app['env']) ?></code></p>
    </main>
</body>
</html>
