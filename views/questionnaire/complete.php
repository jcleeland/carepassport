<h1>Question path complete</h1>

<div class="panel">
    <p><?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?> has reached the end of <?= $view->escape($path['title']) ?>.</p>
    <p><?= $view->escape($completed) ?> of <?= $view->escape($total) ?> questions have been saved or skipped.</p>
    <p>The review, visibility controls, photo upload and PDFs will be added in later MVP slices.</p>
</div>

<div class="actions">
    <a class="button" href="/questionnaire/question?position=1">Review questions</a>
    <a href="/questionnaire/select">Change path</a>
    <a href="/resident/edit">Back to profile</a>
</div>
