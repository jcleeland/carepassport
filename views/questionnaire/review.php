<h1>Review answers</h1>

<p>Review the answers saved for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?>. You can leave any answer blank.</p>

<?php if ($status): ?>
    <div class="notice"><?= $view->escape($status) ?></div>
<?php endif; ?>

<section class="notice">
    <h2>Visibility</h2>
    <p>Please review each answer carefully. Posters may be seen by anyone entering the room or support space. A bedside or support booklet may be read by supporters, visitors or others nearby.</p>
    <?php foreach ($visibilityOptions as $option): ?>
        <p><strong><?= $view->escape($option['label']) ?>:</strong> <?= $view->escape($option['description']) ?></p>
    <?php endforeach; ?>
</section>

<?php if ($answers === []): ?>
    <div class="panel">
        <p>No answers or skipped questions have been saved for this path yet.</p>
    </div>
    <div class="actions">
        <a class="button" href="/questionnaire/question?position=1">Start questions</a>
        <a href="/questionnaire/select">Change path</a>
    </div>
<?php else: ?>
    <form method="post" action="/questionnaire/review">
        <div class="panel section">
            <p><strong><?= $view->escape($path['title']) ?></strong></p>
            <p><?= $view->escape($completed) ?> of <?= $view->escape($total) ?> questions saved or skipped.</p>
        </div>

        <?php foreach ($sections as $section): ?>
            <section class="panel section">
                <h2><?= $view->escape($section['title']) ?></h2>

                <?php foreach ($section['answers'] as $answer): ?>
                    <div class="field">
                        <label for="answer_<?= $view->escape($answer['question_id']) ?>">
                            <?= $view->escape($answer['question_text']) ?>
                        </label>

                        <?php if ((int) $answer['skipped'] === 1 && ($answer['answer_text'] ?? '') === ''): ?>
                            <div class="hint">Skipped. Add an answer here if you want to include one.</div>
                        <?php endif; ?>

                        <textarea id="answer_<?= $view->escape($answer['question_id']) ?>" name="answer_text[<?= $view->escape($answer['question_id']) ?>]"><?= $view->escape($answer['answer_text'] ?? '') ?></textarea>

                        <fieldset class="field">
                            <legend>Visibility</legend>
                            <?php foreach ($visibilityOptions as $value => $option): ?>
                                <label class="option">
                                    <input
                                        type="radio"
                                        name="visibility[<?= $view->escape($answer['question_id']) ?>]"
                                        value="<?= $view->escape($value) ?>"
                                        <?= $answer['visibility'] === $value ? 'checked' : '' ?>
                                    >
                                    <span>
                                        <strong><?= $view->escape($option['label']) ?></strong>
                                        <small><?= $view->escape($option['description']) ?></small>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>

        <div class="actions">
            <button type="submit">Save review</button>
            <a href="/questionnaire/question?position=1">Back to questions</a>
            <a href="/questionnaire/select">Change path</a>
        </div>
    </form>
<?php endif; ?>
