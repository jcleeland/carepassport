<h1>Dashboard</h1>

<?php if ($status): ?>
    <div class="notice"><?= $view->escape($status) ?></div>
<?php endif; ?>

<div class="actions">
    <a class="button" href="/">Start a new profile</a>
</div>

<?php if ($residentCards === []): ?>
    <div class="panel section">
        <p>No profiles are saved to this account yet.</p>
    </div>
<?php else: ?>
    <section class="dashboard-list">
        <?php foreach ($residentCards as $card): ?>
            <?php $resident = $card['resident']; ?>
            <article class="panel dashboard-card">
                <div class="dashboard-card-main">
                    <div>
                        <h2><?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?></h2>
                        <?php if (($resident['preferred_name'] ?? '') !== ''): ?>
                            <p class="hint">Full name: <?= $view->escape($resident['full_name']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="status-pill"><?= $view->escape($card['status']) ?></div>
                </div>

                <dl class="dashboard-meta">
                    <div>
                        <dt>Support context</dt>
                        <dd><?= $view->escape($resident['support_context'] ?: 'Not set') ?></dd>
                    </div>
                    <div>
                        <dt>Last updated</dt>
                        <dd><?= $view->escape($resident['updated_at'] ?? $resident['created_at']) ?></dd>
                    </div>
                    <div>
                        <dt>Questions</dt>
                        <dd><?= $view->escape($card['answer_count']) ?> of <?= $view->escape($card['question_count']) ?> saved or skipped</dd>
                    </div>
                    <div>
                        <dt>Portrait</dt>
                        <dd><?= $card['has_portrait'] ? 'Uploaded' : 'Not uploaded' ?></dd>
                    </div>
                </dl>

                <p><?= $view->escape($card['detail']) ?></p>

                <div class="actions">
                    <a class="button" href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=<?= $view->escape($card['continue_target']) ?>&position=<?= $view->escape($card['continue_position']) ?>">Continue</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=profile">Edit profile</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=review">Review answers</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=photo">Upload or replace photo</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=poster_a">Preview Poster A</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=poster_b">Preview Poster B</a>
                    <a href="/resident/use?id=<?= $view->escape($resident['id']) ?>&target=booklet">Preview Full Booklet</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
