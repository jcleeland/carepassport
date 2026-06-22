<?php

$visibleZones = array_values(array_filter(
    $zones,
    static fn (array $zone): bool => $zone['answers'] !== [],
));

$zoneLimits = [
    'mornings' => 220,
    'evenings_and_sleep' => 220,
    'communication_style' => 210,
    'if_i_seem_upset' => 220,
    'things_to_ask_first' => 190,
    'comfort_items_or_routines' => 220,
];
$shorten = static function (string $text, int $limit): string {
    $text = trim(preg_replace('/\s+/', ' ', $text) ?? $text);

    if (mb_strlen($text) <= $limit) {
        return $text;
    }

    $cut = mb_substr($text, 0, $limit);
    $lastSpace = mb_strrpos($cut, ' ');

    if ($lastSpace !== false && $lastSpace > (int) floor($limit * 0.65)) {
        $cut = mb_substr($cut, 0, $lastSpace);
    }

    return rtrim($cut, " \t\n\r\0\x0B.,;:") . '...';
};
$zoneAnswerText = static function (array $zone) use ($shorten, $zoneLimits): array {
    $limit = $zoneLimits[$zone['zone_key']] ?? 210;

    return array_map(
        static fn (array $answer): string => $shorten((string) $answer['answer_text'], $limit),
        $zone['answers'],
    );
};
$preferredName = trim((string) ($resident['preferred_name'] ?: $resident['full_name']));
?>

<div class="output-toolbar">
    <div>
        <h1>Poster B preview</h1>
        <p>Use your browser print dialog and choose Save as PDF if you want a local PDF copy. For best results, print at actual size with background graphics enabled.</p>
    </div>
    <div class="actions output-actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a href="/output">Output hub</a>
        <a href="/output/poster-a">Poster A preview</a>
        <a href="/output/booklet">Full Booklet preview</a>
        <a href="/questionnaire/review">Back to review and visibility</a>
        <a href="/photo/portrait">Back to photo upload</a>
        <a href="/dashboard">Dashboard</a>
    </div>
</div>

<article class="poster-b-preview" aria-label="<?= $view->escape($outputTemplate['title']) ?>">
    <header class="poster-b-header">
        <p><?= $view->escape($outputTemplate['title']) ?></p>
        <h1><?= $view->escape($preferredName !== '' ? $preferredName : 'Helpful things to know') ?></h1>
    </header>

    <?php if ($visibleZones === []): ?>
        <section class="poster-b-empty">
            <h2>No poster-visible support details yet</h2>
            <p>Use review and visibility to choose answers that may appear on posters.</p>
        </section>
    <?php else: ?>
        <section class="poster-b-zone-grid">
            <?php foreach ($visibleZones as $zone): ?>
                <section class="poster-b-zone poster-b-zone-<?= $view->escape($zone['zone_key']) ?>">
                    <h2><?= $view->escape($zone['label']) ?></h2>
                    <?php foreach ($zoneAnswerText($zone) as $answerText): ?>
                        <p><?= $view->escape($answerText) ?></p>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <footer class="poster-b-footer">
        This document shares personal history, preferences and routines to help people understand the person. It does not replace assessment, planning, procedures or professional judgement.
    </footer>
</article>
