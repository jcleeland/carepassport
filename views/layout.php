<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $view->escape($title ?? 'Care Passport') ?></title>
    <style>
        :root {
            color-scheme: light;
            --border: #d7ded9;
            --ink: #1d2a24;
            --muted: #5b6a62;
            --surface: #f7f9f7;
            --accent: #2f6f5e;
            --error: #9f1f1f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: #ffffff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }

        header, main {
            max-width: 860px;
            margin: 0 auto;
            padding: 24px;
        }

        header {
            border-bottom: 1px solid var(--border);
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(2rem, 5vw, 3.25rem);
            line-height: 1.08;
        }

        h2 {
            margin: 0 0 18px;
            font-size: 1.8rem;
        }

        legend {
            font-weight: 700;
            margin-bottom: 10px;
        }

        fieldset {
            border: 0;
            margin: 0 0 18px;
            padding: 0;
        }

        p {
            margin: 0 0 16px;
        }

        a {
            color: var(--accent);
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            padding: 20px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        button, .button {
            border: 0;
            border-radius: 6px;
            background: var(--accent);
            color: white;
            cursor: pointer;
            display: inline-block;
            font: inherit;
            font-weight: 700;
            padding: 11px 16px;
            text-decoration: none;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
        }

        input, select, textarea {
            width: 100%;
            border: 1px solid #b9c4bd;
            border-radius: 6px;
            color: var(--ink);
            font: inherit;
            padding: 10px 11px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .field {
            margin-bottom: 18px;
        }

        .hint {
            color: var(--muted);
            font-size: .94rem;
            margin-top: 5px;
        }

        .error {
            color: var(--error);
            font-weight: 700;
            margin-top: 5px;
        }

        .notice {
            border-left: 4px solid var(--accent);
            background: #eef6f2;
            padding: 12px 14px;
            margin-bottom: 18px;
        }

        .section {
            margin-bottom: 18px;
        }

        .option {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-weight: 400;
            margin: 0 0 12px;
        }

        .option input {
            width: auto;
            margin-top: 4px;
        }

        .option small {
            color: var(--muted);
            display: block;
            font-size: .94rem;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <header>
        <strong>Care Passport</strong>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>
