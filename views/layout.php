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

        .portrait-preview {
            aspect-ratio: 1 / 1;
            border: 1px solid var(--border);
            border-radius: 8px;
            display: block;
            height: auto;
            max-width: 280px;
            object-fit: cover;
            width: 100%;
        }

        .output-actions {
            margin-bottom: 18px;
        }

        .poster-a-preview {
            background: #ffffff;
            border: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(29, 42, 36, .08);
            display: grid;
            gap: 7mm;
            margin: 0 auto;
            min-height: 297mm;
            padding: 12mm;
            width: 210mm;
        }

        .poster-a-header {
            border-bottom: 2px solid var(--ink);
            margin: 0;
            padding: 0 0 4mm;
        }

        .poster-a-header p {
            font-size: 24pt;
            font-weight: 800;
            letter-spacing: 0;
            line-height: 1.05;
            margin: 0;
        }

        .poster-a-hero {
            align-items: stretch;
            display: grid;
            gap: 8mm;
            grid-template-columns: minmax(0, 70mm) 1fr;
        }

        .poster-a-hero-without-photo {
            grid-template-columns: 1fr;
        }

        .poster-a-photo-wrap {
            align-self: start;
        }

        .poster-a-photo {
            aspect-ratio: 4 / 5;
            border-radius: 3mm;
            display: block;
            height: auto;
            object-fit: cover;
            width: 70mm;
        }

        .poster-a-name-block {
            align-self: center;
        }

        .poster-a-kicker {
            color: var(--muted);
            font-size: 11pt;
            font-weight: 800;
            margin: 0 0 2mm;
        }

        .poster-a-name-block h1 {
            font-size: 38pt;
            line-height: 1;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .poster-a-caption {
            font-size: 16pt;
            font-weight: 650;
            line-height: 1.25;
            margin: 5mm 0 0;
        }

        .poster-a-zone-grid {
            display: grid;
            gap: 5mm;
            grid-template-columns: 1fr 1fr;
        }

        .poster-a-zone {
            border-top: 1px solid var(--border);
            padding-top: 3mm;
        }

        .poster-a-zone-life_in_brief,
        .poster-a-zone-please_know {
            grid-column: 1 / -1;
        }

        .poster-a-zone h2 {
            font-size: 13pt;
            line-height: 1.15;
            margin: 0 0 2mm;
        }

        .poster-a-zone p {
            font-size: 11pt;
            line-height: 1.28;
            margin: 0 0 2mm;
            overflow-wrap: anywhere;
        }

        .poster-a-footer {
            align-self: end;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 7pt;
            line-height: 1.25;
            padding-top: 3mm;
        }

        @media (max-width: 900px) {
            .poster-a-preview {
                min-height: auto;
                width: 100%;
            }

            .poster-a-hero,
            .poster-a-zone-grid {
                grid-template-columns: 1fr;
            }

            .poster-a-photo {
                max-width: 260px;
                width: 100%;
            }
        }

        @media print {
            @page {
                margin: 0;
                size: A4 portrait;
            }

            body {
                background: #ffffff;
            }

            body > header,
            .output-actions {
                display: none;
            }

            main {
                max-width: none;
                padding: 0;
            }

            .poster-a-preview {
                border: 0;
                box-shadow: none;
                min-height: 297mm;
                page-break-after: avoid;
                page-break-inside: avoid;
                width: 210mm;
            }
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
