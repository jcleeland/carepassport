<?php

$displayName = trim((string) ($resident['preferred_name'] ?: $resident['full_name']));
$bookletTotal = (int) $counts['poster_visible'] + (int) $counts['booklet_visible'];
?>

<h1>Final review and outputs</h1>

<p>Review what is ready for <?= $view->escape($displayName !== '' ? $displayName : 'this person') ?> before opening the printable previews.</p>

<section class="notice output-reminder">
    <h2>Before printing or saving</h2>
    <p>Posters may be seen by anyone nearby. The booklet may be read by others if it is left out. Private answers are excluded from printed outputs.</p>
    <p>For now, open each preview and use your browser print dialog. Choose Save as PDF if you want a local PDF copy.</p>
</section>

<section class="panel output-summary">
    <div>
        <strong>Portrait photo</strong>
        <span><?= $photo !== null ? 'Uploaded' : 'Not uploaded' ?></span>
    </div>
    <div>
        <strong>Poster-visible answers</strong>
        <span><?= $view->escape($counts['poster_visible']) ?></span>
    </div>
    <div>
        <strong>Booklet-visible answers</strong>
        <span><?= $view->escape($counts['booklet_visible']) ?></span>
    </div>
    <div>
        <strong>Private answers</strong>
        <span><?= $view->escape($counts['private']) ?></span>
    </div>
    <div>
        <strong>Skipped answers</strong>
        <span><?= $view->escape($counts['skipped']) ?></span>
    </div>
</section>

<section class="output-card-grid" aria-label="Output previews">
    <?php foreach ($cards as $card): ?>
        <article class="panel output-card output-card-<?= $view->escape($card['slug']) ?>">
            <div>
                <h2><?= $view->escape($card['title']) ?></h2>
                <?php if (($card['description'] ?? '') !== ''): ?>
                    <p><?= $view->escape($card['description']) ?></p>
                <?php endif; ?>
            </div>

            <dl class="output-card-status">
                <div>
                    <dt>Status</dt>
                    <dd><?= $view->escape($card['status']) ?></dd>
                </div>
                <div>
                    <dt>Content</dt>
                    <dd><?= $view->escape($card['detail']) ?></dd>
                </div>
                <?php if ($card['slug'] === 'full_booklet'): ?>
                    <div>
                        <dt>Included in booklet</dt>
                        <dd><?= $view->escape($bookletTotal) ?> visible answer<?= $bookletTotal === 1 ? '' : 's' ?></dd>
                    </div>
                <?php endif; ?>
            </dl>

            <div class="actions">
                <a class="button" href="<?= $view->escape($card['href']) ?>">Open preview</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<div class="actions">
    <a href="/dashboard">Dashboard</a>
    <a href="/questionnaire/review">Review or edit answers</a>
    <a href="/photo/portrait">Upload or replace photo</a>
</div>
