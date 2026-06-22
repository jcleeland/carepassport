<h1>Ready for the next step</h1>

<?php if ($flash): ?>
    <div class="notice"><?= $view->escape($flash) ?></div>
<?php endif; ?>

<div class="panel">
    <p>The profile and consent details for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?> have been saved.</p>
    <p>The questionnaire flow is not part of this slice. It will be added in the next product workflow step.</p>
</div>

<div class="actions">
    <a class="button" href="/consent">Review consent</a>
    <a href="/resident/edit">Back to profile</a>
</div>
