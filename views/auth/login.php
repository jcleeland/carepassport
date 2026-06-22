<h1>Log in</h1>

<p>Log in to return to saved Care Passport profiles.</p>

<?php if ($status): ?>
    <div class="notice"><?= $view->escape($status) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="notice">Please check your details and try again.</div>
<?php endif; ?>

<form method="post" action="/login" class="panel">
    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= $view->escape($data['email']) ?>" autocomplete="email" required>
        <?php if (isset($errors['email'])): ?><div class="error"><?= $view->escape($errors['email']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <?php if (isset($errors['password'])): ?><div class="error"><?= $view->escape($errors['password']) ?></div><?php endif; ?>
    </div>

    <div class="actions">
        <button type="submit">Log in</button>
        <a href="/register">Create account</a>
    </div>
</form>
