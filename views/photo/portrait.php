<h1>Portrait photo</h1>

<p>Upload one portrait photo for <?= $view->escape($resident['preferred_name'] ?: $resident['full_name']) ?>. The photo will be used on Poster A and in the booklet.</p>

<?php if ($status): ?>
    <div class="notice"><?= $view->escape($status) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="notice">Please check the photo and try again.</div>
<?php endif; ?>

<?php if ($photo !== null): ?>
    <section class="panel section">
        <h2>Current portrait</h2>
        <img src="/photo/portrait/preview" alt="Portrait preview" class="portrait-preview">
        <p class="hint">Uploading a new photo will replace this one.</p>
    </section>
<?php endif; ?>

<form method="post" action="/photo/portrait" enctype="multipart/form-data" class="panel">
    <div class="field">
        <label for="portrait_photo">Choose a photo</label>
        <input id="portrait_photo" name="portrait_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <div class="hint">JPG, PNG or WebP. Maximum 5 MB. A square version will be prepared for printed outputs.</div>
        <?php if (isset($errors['portrait_photo'])): ?><div class="error"><?= $view->escape($errors['portrait_photo']) ?></div><?php endif; ?>
    </div>

    <div class="actions">
        <button type="submit"><?= $photo !== null ? 'Replace photo' : 'Upload photo' ?></button>
        <a href="/photo/portrait/skip"><?= $photo !== null ? 'Continue' : 'Continue without a photo' ?></a>
        <a href="/questionnaire/review">Back to review</a>
    </div>
</form>
