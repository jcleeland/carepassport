<?php

$zoneByKey = [];

foreach ($zones as $zone) {
    $zoneByKey[$zone['zone_key']] = $zone;
}

$visibleZones = array_values(array_filter(
    $zones,
    static fn (array $zone): bool => ! in_array($zone['zone_key'], ['photo', 'preferred_name', 'photo_caption'], true)
        && $zone['answers'] !== [],
));

$zoneLimits = [
    'photo_caption' => 120,
    'what_to_call_me' => 180,
    'life_in_brief' => 130,
    'talk_to_me_about' => 180,
    'feel_good_or_comfortable' => 180,
    'please_know' => 180,
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
    $limit = $zoneLimits[$zone['zone_key']] ?? 260;

    return array_map(
        static fn (array $answer): string => $shorten((string) $answer['answer_text'], $limit),
        $zone['answers'],
    );
};
$captionZone = $zoneByKey['photo_caption'] ?? null;
$caption = ($captionZone !== null && ($captionZone['answers'][0]['answer_text'] ?? '') !== '')
    ? $shorten((string) $captionZone['answers'][0]['answer_text'], $zoneLimits['photo_caption'])
    : null;
$preferredName = trim((string) ($resident['preferred_name'] ?: $resident['full_name']));
?>

<div class="output-toolbar">
    <div>
        <h1>Poster A preview</h1>
        <p>Use your browser print dialog and choose Save as PDF if you want a local PDF copy. For best results, print at actual size with background graphics enabled.</p>
    </div>
    <div class="actions output-actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a href="/output">Output hub</a>
        <a href="/output/poster-b">Poster B preview</a>
        <a href="/output/booklet">Full Booklet preview</a>
        <a href="/questionnaire/review">Back to review and visibility</a>
        <a href="/photo/portrait">Back to photo upload</a>
        <a href="/dashboard">Dashboard</a>
    </div>
</div>

<article class="poster-a-preview" aria-label="<?= $view->escape($outputTemplate['title']) ?>">
    <header class="poster-a-header">
        <p><?= $view->escape($outputTemplate['title']) ?></p>
    </header>

    <section class="poster-a-hero <?= $photo !== null ? 'poster-a-hero-with-photo' : 'poster-a-hero-without-photo' ?>">
        <?php if ($photo !== null): ?>
            <div class="poster-a-photo-wrap">
                <img src="/photo/portrait/preview" alt="Portrait of <?= $view->escape($preferredName) ?>" class="poster-a-photo">
            </div>
        <?php else: ?>
            <div class="poster-a-photo-fallback" aria-hidden="true">
                <span><?= $view->escape(mb_substr($preferredName !== '' ? $preferredName : 'Care Passport', 0, 1)) ?></span>
            </div>
        <?php endif; ?>

        <div class="poster-a-name-block">
            <?php if (isset($zoneByKey['preferred_name'])): ?>
                <p class="poster-a-kicker"><?= $view->escape($zoneByKey['preferred_name']['label']) ?></p>
            <?php endif; ?>
            <h1><?= $view->escape($preferredName !== '' ? $preferredName : 'Who I Am') ?></h1>
            <?php if ($caption !== null): ?>
                <p class="poster-a-caption"><?= $view->escape($caption) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($visibleZones !== []): ?>
        <section class="poster-a-zone-grid">
            <?php foreach ($visibleZones as $zone): ?>
                <section class="poster-a-zone poster-a-zone-<?= $view->escape($zone['zone_key']) ?>">
                    <h2><?= $view->escape($zone['label']) ?></h2>
                    <?php foreach ($zoneAnswerText($zone) as $answerText): ?>
                        <p><?= $view->escape($answerText) ?></p>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <footer class="poster-a-footer">
        This document shares personal history, preferences and routines to help people understand the person. It does not replace assessment, planning, procedures or professional judgement.
    </footer>
</article>
