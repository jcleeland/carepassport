<h1>Question path complete</h1>

<div class="panel">
    <p><?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?> has reached the end of <?= $view->escape($path['title']) ?>.</p>
    <p><?= $view->escape($completed) ?> of <?= $view->escape($total) ?> questions have been saved or skipped.</p>
    <p>Review answers and visibility before opening the printable previews.</p>
</div>

<div class="actions">
    <a class="button" href="/questionnaire/review">Review answers</a>
    <a href="/output">Output hub</a>
    <a href="/photo/portrait">Portrait photo</a>
    <a href="/questionnaire/select">Change path</a>
    <a href="/resident/edit">Back to profile</a>
</div>
