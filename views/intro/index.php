<h1>Before you begin</h1>

<p>This profile is for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?>.</p>

<?php foreach ($introPages as $page): ?>
    <section class="panel section">
        <h2><?= $view->escape($page['title']) ?></h2>
        <?php foreach (preg_split('/\R{2,}/', trim((string) $page['body_markdown'])) ?: [] as $paragraph): ?>
            <?php if (trim($paragraph) !== ''): ?>
                <p><?= nl2br($view->escape(trim($paragraph))) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
<?php endforeach; ?>

<div class="actions">
    <a class="button" href="/consent">Continue</a>
    <a href="/resident/edit">Back to profile</a>
</div>
