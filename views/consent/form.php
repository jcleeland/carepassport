<h1>Consent and completion mode</h1>

<p>This step records who is completing the profile for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?>.</p>

<?php if ($errors !== []): ?>
    <div class="notice">
        Please check the highlighted fields and try again.
    </div>
<?php endif; ?>

<form method="post" action="/consent" class="panel">
    <fieldset class="field">
        <legend>Who is completing this?</legend>
        <?php foreach ($completionModes as $mode): ?>
            <label class="option">
                <input type="radio" name="completion_mode" value="<?= $view->escape($mode['slug']) ?>" <?= ($data['completion_mode'] === $mode['slug']) ? 'checked' : '' ?>>
                <span>
                    <strong><?= $view->escape($mode['label']) ?></strong>
                    <?php if ($mode['description']): ?>
                        <small><?= $view->escape($mode['description']) ?></small>
                    <?php endif; ?>
                </span>
            </label>
        <?php endforeach; ?>
        <?php if (isset($errors['completion_mode'])): ?><div class="error"><?= $view->escape($errors['completion_mode']) ?></div><?php endif; ?>
    </fieldset>

    <div class="field">
        <label for="helper_name">Helper or proxy name</label>
        <input id="helper_name" name="helper_name" value="<?= $view->escape($data['helper_name']) ?>">
        <div class="hint">Required when helping someone or completing this on their behalf.</div>
        <?php if (isset($errors['helper_name'])): ?><div class="error"><?= $view->escape($errors['helper_name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="helper_relationship">Relationship to the person</label>
        <input id="helper_relationship" name="helper_relationship" value="<?= $view->escape($data['helper_relationship']) ?>">
        <div class="hint">For example, family member, friend, partner, advocate or trusted supporter.</div>
        <?php if (isset($errors['helper_relationship'])): ?><div class="error"><?= $view->escape($errors['helper_relationship']) ?></div><?php endif; ?>
    </div>

    <section class="notice">
        <h2>Privacy and sharing</h2>
        <p>All questions are optional. Answers can be edited later, and answers can be excluded from printed outputs.</p>
        <p>Posters may be seen by anyone entering the room or support space. A bedside or support booklet may be read by staff, visitors or others nearby. Private answers can be excluded from printed outputs.</p>
        <p>This app records the completion pathway but does not assess legal capacity.</p>
    </section>

    <label class="option">
        <input type="checkbox" name="acknowledged" value="1" <?= $data['acknowledged'] === '1' ? 'checked' : '' ?>>
        <span>I understand these privacy and sharing notes.</span>
    </label>
    <?php if (isset($errors['acknowledged'])): ?><div class="error"><?= $view->escape($errors['acknowledged']) ?></div><?php endif; ?>

    <div class="actions">
        <button type="submit">Save and continue</button>
        <a href="/intro">Back to intro</a>
        <a href="/resident/edit">Back to profile</a>
    </div>
</form>
