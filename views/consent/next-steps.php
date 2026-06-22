<h1>Ready for the next step</h1>

<?php if ($flash): ?>
    <div class="notice"><?= $view->escape($flash) ?></div>
<?php endif; ?>

<div class="panel">
    <p>The profile and consent details for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?> have been saved.</p>
    <p>You can now choose a question path. Every question is optional.</p>
</div>

<div class="actions">
    <a class="button" href="/questionnaire/select">Choose question path</a>
    <a href="/consent">Review consent</a>
    <a href="/resident/edit">Back to profile</a>
</div>
