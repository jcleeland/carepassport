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

$captionZone = $zoneByKey['photo_caption'] ?? null;
$caption = ($captionZone !== null && ($captionZone['answers'][0]['answer_text'] ?? '') !== '')
    ? $captionZone['answers'][0]['answer_text']
    : null;
$preferredName = trim((string) ($resident['preferred_name'] ?: $resident['full_name']));
?>

<div class="actions output-actions">
    <a href="/questionnaire/review">Back to review and visibility</a>
    <a href="/photo/portrait">Back to photo upload</a>
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
                    <?php foreach ($zone['answers'] as $answer): ?>
                        <p><?= nl2br($view->escape($answer['answer_text'])) ?></p>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <footer class="poster-a-footer">
        This document shares personal history, preferences and routines to help people understand the person. It does not replace assessment, planning, procedures or professional judgement.
    </footer>
</article>
