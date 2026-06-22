<?php

$visibleSections = array_values(array_filter(
    $sections,
    static fn (array $section): bool => $section['answers'] !== [],
));

$preferredName = trim((string) ($resident['preferred_name'] ?: $resident['full_name']));
$displayName = $preferredName !== '' ? $preferredName : 'Care Passport';
$supportContextLabel = is_array($supportContext ?? null) ? ($supportContext['label'] ?? null) : null;
$generatedDate = date('j M Y');

$profileRows = array_filter([
    'Full name' => trim((string) ($resident['full_name'] ?? '')),
    'Preferred name' => trim((string) ($resident['preferred_name'] ?? '')),
    'Support context' => is_string($supportContextLabel) ? $supportContextLabel : '',
    'Service, support setting or location' => trim((string) ($resident['service_location_name'] ?? '')),
    'Room, location or reference' => trim((string) ($resident['location_reference'] ?? '')),
    'Primary supporter or contact' => trim((string) ($resident['primary_supporter_name'] ?? '')),
], static fn (string $value): bool => $value !== '');

$completionRows = [];

if (is_array($completionContext ?? null)) {
    $completionRows['Completion mode'] = (string) ($completionContext['completion_mode_label'] ?? '');

    if (($completionContext['helper_name'] ?? '') !== '') {
        $completionRows['Helper or proxy name'] = (string) $completionContext['helper_name'];
    }

    if (($completionContext['helper_relationship'] ?? '') !== '') {
        $completionRows['Relationship'] = (string) $completionContext['helper_relationship'];
    }

    if (($completionContext['consent_acknowledged_at'] ?? '') !== '') {
        $completionRows['Consent recorded'] = (string) $completionContext['consent_acknowledged_at'];
    }
}

$safeClass = static fn (string $value): string => preg_replace('/[^a-z0-9_-]+/', '-', strtolower($value)) ?? 'section';
$answerHtml = static function (string $answer) use ($view): string {
    return nl2br($view->escape(trim($answer)));
};
?>

<div class="output-toolbar">
    <div>
        <h1>Full Booklet preview</h1>
        <p>Use your browser print dialog and choose Save as PDF if you want a local PDF copy. For best results, print on A4 with background graphics enabled.</p>
    </div>
    <div class="actions output-actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a href="/output">Output hub</a>
        <a href="/output/poster-a">Poster A preview</a>
        <a href="/output/poster-b">Poster B preview</a>
        <a href="/questionnaire/review">Back to review and visibility</a>
        <a href="/photo/portrait">Back to photo upload</a>
        <a href="/dashboard">Dashboard</a>
    </div>
</div>

<article class="booklet-preview" aria-label="<?= $view->escape($outputTemplate['title']) ?>">
    <section class="booklet-page booklet-cover">
        <div class="booklet-cover-mark"><?= $view->escape($outputTemplate['title']) ?></div>
        <div class="booklet-cover-layout <?= $photo !== null ? 'booklet-cover-with-photo' : 'booklet-cover-without-photo' ?>">
            <?php if ($photo !== null): ?>
                <img src="/photo/portrait/preview" alt="Portrait of <?= $view->escape($displayName) ?>" class="booklet-photo">
            <?php endif; ?>

            <div class="booklet-cover-copy">
                <h1><?= $view->escape($displayName) ?></h1>
                <?php if (($resident['full_name'] ?? '') !== '' && $resident['full_name'] !== $displayName): ?>
                    <p><?= $view->escape($resident['full_name']) ?></p>
                <?php endif; ?>
                <?php if (is_string($outputTemplate['description'] ?? null) && $outputTemplate['description'] !== ''): ?>
                    <p><?= $view->escape($outputTemplate['description']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($profileRows !== []): ?>
            <dl class="booklet-profile-grid">
                <?php foreach ($profileRows as $label => $value): ?>
                    <div>
                        <dt><?= $view->escape($label) ?></dt>
                        <dd><?= $view->escape($value) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        <?php endif; ?>

        <footer class="booklet-cover-footer">Generated <?= $view->escape($generatedDate) ?></footer>
    </section>

    <section class="booklet-page booklet-note-page">
        <h1>About this document</h1>
        <p>This document shares personal history, preferences and routines to help people understand the person. It does not replace assessment, planning, procedures or professional judgement.</p>
        <p>Printed copies may be read by people nearby or by people who can access them in the support setting. Answers marked private are excluded from this preview.</p>

        <?php if ($completionRows !== []): ?>
            <h2>Completion context</h2>
            <dl class="booklet-profile-grid booklet-context-grid">
                <?php foreach ($completionRows as $label => $value): ?>
                    <?php if (trim($value) !== ''): ?>
                        <div>
                            <dt><?= $view->escape($label) ?></dt>
                            <dd><?= $view->escape($value) ?></dd>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </dl>
        <?php endif; ?>
    </section>

    <?php if ($visibleSections === []): ?>
        <section class="booklet-page">
            <h1>No booklet-visible answers yet</h1>
            <p>Use review and visibility to choose answers that may appear in the booklet.</p>
        </section>
    <?php else: ?>
        <?php foreach ($visibleSections as $section): ?>
            <section class="booklet-page booklet-answer-section booklet-section-<?= $view->escape($safeClass((string) $section['zone_key'])) ?>">
                <h1><?= $view->escape($section['label']) ?></h1>

                <?php foreach ($section['answers'] as $answer): ?>
                    <section class="booklet-answer-block">
                        <h2><?= $view->escape($answer['question_text']) ?></h2>
                        <p><?= $answerHtml((string) $answer['answer_text']) ?></p>
                    </section>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</article>
