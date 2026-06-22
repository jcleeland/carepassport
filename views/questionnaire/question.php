<h1>Question <?= $view->escape($position) ?> of <?= $view->escape($total) ?></h1>

<div class="panel section">
    <p><strong><?= $view->escape($path['title']) ?></strong></p>
    <p><?= $view->escape($completed) ?> of <?= $view->escape($total) ?> questions saved or skipped.</p>
    <progress value="<?= $view->escape($position) ?>" max="<?= $view->escape($total) ?>" style="width: 100%;"></progress>
</div>

<form method="post" action="/questionnaire/question?position=<?= $view->escape($position) ?>" class="panel">
    <p class="hint"><?= $view->escape($question['section_title']) ?></p>
    <h2><?= $view->escape($question['question_text']) ?></h2>

    <?php if ($question['help_text']): ?>
        <p class="hint"><?= $view->escape($question['help_text']) ?></p>
    <?php endif; ?>

    <div class="field">
        <label for="answer_text">Answer</label>
        <textarea id="answer_text" name="answer_text"><?= $view->escape($answer['answer_text'] ?? '') ?></textarea>
        <div class="hint">Optional. Leave blank and continue, or use Skip.</div>
    </div>

    <div class="hint">
        Default visibility for this answer: <?= $view->escape($question['default_visibility'] ?: 'booklet') ?>.
        Full visibility review will be added later.
    </div>

    <div class="actions">
        <?php if ($position > 1): ?>
            <button type="submit" name="action" value="back">Save and back</button>
        <?php endif; ?>
        <button type="submit" name="action" value="next">Save and continue</button>
        <button type="submit" name="action" value="skip">Skip</button>
        <a href="/questionnaire/select">Change path</a>
    </div>
</form>
