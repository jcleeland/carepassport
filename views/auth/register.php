<h1>Create account</h1>

<p>Create an account to return to saved Care Passport profiles.</p>

<?php if ($status): ?>
    <div class="notice"><?= $view->escape($status) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="notice">Please check the highlighted fields and try again.</div>
<?php endif; ?>

<form method="post" action="/register" class="panel">
    <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" value="<?= $view->escape($data['name']) ?>" autocomplete="name" required>
        <?php if (isset($errors['name'])): ?><div class="error"><?= $view->escape($errors['name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= $view->escape($data['email']) ?>" autocomplete="email" required>
        <?php if (isset($errors['email'])): ?><div class="error"><?= $view->escape($errors['email']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" required>
        <div class="hint">Use at least 8 characters.</div>
        <?php if (isset($errors['password'])): ?><div class="error"><?= $view->escape($errors['password']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        <?php if (isset($errors['password_confirmation'])): ?><div class="error"><?= $view->escape($errors['password_confirmation']) ?></div><?php endif; ?>
    </div>

    <div class="actions">
        <button type="submit">Create account</button>
        <a href="/login">Log in instead</a>
    </div>
</form>
