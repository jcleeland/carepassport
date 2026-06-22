<h1><?= $view->escape($title) ?></h1>

<?php if ($flash): ?>
    <div class="notice"><?= $view->escape($flash) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="notice">
        Please check the highlighted fields and try again.
    </div>
<?php endif; ?>

<form method="post" action="<?= $view->escape($action) ?>" class="panel">
    <div class="field">
        <label for="full_name">Full name</label>
        <input id="full_name" name="full_name" value="<?= $view->escape($resident['full_name'] ?? '') ?>" autocomplete="name" required>
        <?php if (isset($errors['full_name'])): ?><div class="error"><?= $view->escape($errors['full_name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="preferred_name">Preferred name</label>
        <input id="preferred_name" name="preferred_name" value="<?= $view->escape($resident['preferred_name'] ?? '') ?>">
        <div class="hint">Optional. This is the name the person likes people to use.</div>
        <?php if (isset($errors['preferred_name'])): ?><div class="error"><?= $view->escape($errors['preferred_name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="support_context">Support context</label>
        <select id="support_context" name="support_context" required>
            <option value="">Choose a support context</option>
            <?php foreach ($supportContexts as $context): ?>
                <option value="<?= $view->escape($context['slug']) ?>" <?= (($resident['support_context'] ?? '') === $context['slug']) ? 'selected' : '' ?>>
                    <?= $view->escape($context['label']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="hint">Used to understand the setting. This does not change the question set.</div>
        <?php if (isset($errors['support_context'])): ?><div class="error"><?= $view->escape($errors['support_context']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="service_location_name">Service, support setting or location name</label>
        <input id="service_location_name" name="service_location_name" value="<?= $view->escape($resident['service_location_name'] ?? '') ?>">
        <div class="hint">Optional. For example, a service name, home support provider, ward, residence or community setting.</div>
        <?php if (isset($errors['service_location_name'])): ?><div class="error"><?= $view->escape($errors['service_location_name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="location_reference">Room, location or reference</label>
        <input id="location_reference" name="location_reference" value="<?= $view->escape($resident['location_reference'] ?? '') ?>">
        <div class="hint">Optional. Use whatever reference is helpful for this support setting.</div>
        <?php if (isset($errors['location_reference'])): ?><div class="error"><?= $view->escape($errors['location_reference']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="primary_supporter_name">Primary supporter or contact name</label>
        <input id="primary_supporter_name" name="primary_supporter_name" value="<?= $view->escape($resident['primary_supporter_name'] ?? '') ?>">
        <div class="hint">Optional. This can be a family member, friend or trusted supporter.</div>
        <?php if (isset($errors['primary_supporter_name'])): ?><div class="error"><?= $view->escape($errors['primary_supporter_name']) ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes"><?= $view->escape($resident['notes'] ?? '') ?></textarea>
        <div class="hint">Optional setup notes for this profile. These are not part of the questionnaire.</div>
        <?php if (isset($errors['notes'])): ?><div class="error"><?= $view->escape($errors['notes']) ?></div><?php endif; ?>
    </div>

    <div class="actions">
        <button type="submit">Save profile</button>
        <?php if (($resident['id'] ?? null) !== null): ?>
            <a class="button" href="/intro">Continue</a>
        <?php endif; ?>
        <a href="/">Back to start</a>
    </div>
</form>
