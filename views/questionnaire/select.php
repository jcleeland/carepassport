<h1>Choose a question path</h1>

<p>Select the question path for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?>. You can skip any question.</p>

<?php if ($errors !== []): ?>
    <div class="notice">Please choose a question path to continue.</div>
<?php endif; ?>

<form method="post" action="/questionnaire/select" class="panel">
    <fieldset class="field">
        <legend>Question path</legend>
        <?php foreach ($paths as $path): ?>
            <label class="option">
                <input type="radio" name="question_path" value="<?= $view->escape($path['slug']) ?>" <?= ($selectedPathId === $path['id']) ? 'checked' : '' ?>>
                <span>
                    <strong><?= $view->escape($path['title']) ?></strong>
                    <small>
                        <?= $view->escape((string) $path['question_count']) ?> questions.
                        <?php if ($path['description']): ?><?= $view->escape($path['description']) ?><?php endif; ?>
                    </small>
                </span>
            </label>
        <?php endforeach; ?>
        <?php if (isset($errors['question_path'])): ?><div class="error"><?= $view->escape($errors['question_path']) ?></div><?php endif; ?>
    </fieldset>

    <div class="actions">
        <button type="submit">Start questions</button>
        <a href="/consent">Back to consent</a>
        <a href="/resident/edit">Back to profile</a>
    </div>
</form>
