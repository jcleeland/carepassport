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

        header {
            align-items: center;
            display: flex;
            gap: 16px;
            justify-content: space-between;
        }

        .top-nav {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .top-nav form {
            margin: 0;
        }

        .link-button {
            background: transparent;
            color: var(--accent);
            font-weight: 400;
            padding: 0;
            text-decoration: underline;
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

        .dashboard-list {
            display: grid;
            gap: 18px;
        }

        .dashboard-card {
            display: grid;
            gap: 16px;
        }

        .dashboard-card-main {
            align-items: start;
            display: flex;
            gap: 16px;
            justify-content: space-between;
        }

        .dashboard-card-main h2 {
            margin-bottom: 6px;
        }

        .dashboard-meta {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            margin: 0;
        }

        .dashboard-meta dt {
            color: var(--muted);
            font-size: .86rem;
            font-weight: 700;
        }

        .dashboard-meta dd {
            margin: 2px 0 0;
        }

        .status-pill {
            background: #e5f1ec;
            border: 1px solid #b8d6ca;
            border-radius: 999px;
            color: var(--ink);
            font-size: .9rem;
            font-weight: 700;
            padding: 6px 10px;
            white-space: nowrap;
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

        .output-toolbar {
            align-items: end;
            display: flex;
            gap: 18px;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .output-toolbar h1 {
            font-size: 2rem;
            margin-bottom: 6px;
        }

        .output-toolbar p {
            color: var(--muted);
            margin-bottom: 0;
            max-width: 620px;
        }

        .output-actions {
            justify-content: flex-end;
            margin-top: 0;
        }

        .poster-a-preview {
            --poster-accent: #2f6f5e;
            --poster-soft: #eef6f2;
            background: #ffffff;
            border: 1px solid #cfd8d2;
            box-shadow: 0 10px 30px rgba(29, 42, 36, .08);
            display: grid;
            gap: 5mm;
            grid-template-rows: auto auto 1fr auto;
            height: 297mm;
            margin: 0 auto;
            overflow: hidden;
            padding: 12mm;
            width: 210mm;
        }

        .poster-a-header {
            border-bottom: 2px solid var(--poster-accent);
            margin: 0;
            padding: 0 0 3mm;
        }

        .poster-a-header p {
            color: var(--poster-accent);
            font-size: 28pt;
            font-weight: 800;
            letter-spacing: 0;
            line-height: 1.05;
            margin: 0;
        }

        .poster-a-hero {
            align-items: stretch;
            display: grid;
            gap: 7mm;
            grid-template-columns: minmax(0, 70mm) 1fr;
        }

        .poster-a-hero-without-photo {
            grid-template-columns: minmax(0, 42mm) 1fr;
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

        .poster-a-photo-fallback {
            align-items: center;
            aspect-ratio: 4 / 5;
            background: var(--poster-soft);
            border: 1px solid #c9ddd4;
            border-radius: 3mm;
            color: var(--poster-accent);
            display: flex;
            justify-content: center;
            width: 42mm;
        }

        .poster-a-photo-fallback span {
            font-size: 40pt;
            font-weight: 800;
            line-height: 1;
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
            font-size: 42pt;
            line-height: 1;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .poster-a-caption {
            font-size: 16pt;
            font-weight: 650;
            line-height: 1.25;
            margin: 5mm 0 0;
            max-width: 96mm;
        }

        .poster-a-zone-grid {
            align-content: start;
            display: grid;
            gap: 4.5mm 5mm;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        .poster-a-zone {
            border-left: 3px solid var(--poster-accent);
            min-width: 0;
            padding: 0 0 0 3mm;
        }

        .poster-a-zone-life_in_brief,
        .poster-a-zone-please_know {
            grid-column: 1 / -1;
        }

        .poster-a-zone h2 {
            color: var(--poster-accent);
            font-size: 13pt;
            line-height: 1.15;
            margin: 0 0 2mm;
        }

        .poster-a-zone p {
            font-size: 11pt;
            line-height: 1.28;
            margin: 0 0 2mm;
            overflow-wrap: anywhere;
            overflow: hidden;
        }

        .poster-a-zone-what_to_call_me p {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .poster-a-zone-life_in_brief p {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .poster-a-zone-talk_to_me_about p,
        .poster-a-zone-feel_good_or_comfortable p,
        .poster-a-zone-please_know p {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .poster-a-footer {
            align-self: end;
            border-top: 1px solid #cfd8d2;
            color: var(--muted);
            font-size: 7pt;
            line-height: 1.25;
            padding-top: 3mm;
        }

        .poster-b-preview {
            --poster-b-accent: #375a74;
            --poster-b-soft: #edf4f8;
            background: #ffffff;
            border: 1px solid #cfd8d2;
            box-shadow: 0 10px 30px rgba(29, 42, 36, .08);
            display: grid;
            gap: 5mm;
            grid-template-rows: auto 1fr auto;
            height: 297mm;
            margin: 0 auto;
            overflow: hidden;
            padding: 12mm;
            width: 210mm;
        }

        .poster-b-header {
            border-bottom: 2px solid var(--poster-b-accent);
            padding-bottom: 4mm;
        }

        .poster-b-header p {
            color: var(--poster-b-accent);
            font-size: 25pt;
            font-weight: 800;
            line-height: 1.08;
            margin: 0 0 2mm;
        }

        .poster-b-header h1 {
            font-size: 21pt;
            line-height: 1.1;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .poster-b-zone-grid {
            align-content: start;
            display: grid;
            gap: 5mm;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        .poster-b-zone {
            background: var(--poster-b-soft);
            border: 1px solid #c8d8e1;
            border-radius: 3mm;
            min-width: 0;
            overflow: hidden;
            padding: 4mm;
        }

        .poster-b-zone h2 {
            color: var(--poster-b-accent);
            font-size: 14pt;
            line-height: 1.12;
            margin: 0 0 2.5mm;
        }

        .poster-b-zone p {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 4;
            font-size: 10.8pt;
            line-height: 1.25;
            margin: 0 0 2.5mm;
            overflow: hidden;
            overflow-wrap: anywhere;
        }

        .poster-b-empty {
            align-self: center;
            background: var(--poster-b-soft);
            border: 1px solid #c8d8e1;
            border-radius: 3mm;
            padding: 8mm;
        }

        .poster-b-empty h2 {
            color: var(--poster-b-accent);
            margin-bottom: 3mm;
        }

        .poster-b-footer {
            align-self: end;
            border-top: 1px solid #cfd8d2;
            color: var(--muted);
            font-size: 7pt;
            line-height: 1.25;
            padding-top: 3mm;
        }

        @media (max-width: 900px) {
            .output-toolbar {
                align-items: stretch;
                display: grid;
            }

            .output-actions {
                justify-content: flex-start;
            }

            .poster-a-preview {
                height: auto;
                min-height: 297mm;
                width: 100%;
            }

            .poster-b-preview {
                height: auto;
                min-height: 297mm;
                width: 100%;
            }

            .poster-a-hero,
            .poster-a-zone-grid,
            .poster-b-zone-grid {
                grid-template-columns: 1fr;
            }

            .poster-a-photo {
                max-width: 260px;
                width: 100%;
            }

            .poster-a-photo-fallback {
                max-width: 180px;
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
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body > header,
            .output-toolbar {
                display: none;
            }

            main {
                max-width: none;
                padding: 0;
            }

            .poster-a-preview {
                border: 0;
                box-shadow: none;
                height: 297mm;
                margin: 0;
                page-break-after: avoid;
                page-break-inside: avoid;
                width: 210mm;
            }

            .poster-b-preview {
                border: 0;
                box-shadow: none;
                height: 297mm;
                margin: 0;
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
        <nav class="top-nav" aria-label="Account">
            <?php if (\CarePassport\Http\Session::get('user_id') !== null): ?>
                <a href="/dashboard">Dashboard</a>
                <form method="post" action="/logout">
                    <button type="submit" class="link-button">Log out</button>
                </form>
            <?php else: ?>
                <a href="/login">Log in</a>
                <a href="/register">Create account</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>
