<h1>Start a Care Passport</h1>

<div class="panel">
    <p>Create a person-centred profile that can later be used to build helpful printable information for people involved in support.</p>
    <p>This first step only creates a temporary session and a basic profile. Consent, questions, photo upload and PDFs come later in the MVP flow.</p>

    <form method="post" action="/start">
        <div class="actions">
            <button type="submit">Start temporary session</button>
            <?php if (\CarePassport\Http\Session::get('user_id') !== null): ?>
                <a href="/resident/new">Create a saved profile</a>
                <a href="/dashboard">Dashboard</a>
            <?php else: ?>
                <a href="/register">Create account</a>
                <a href="/login">Log in</a>
            <?php endif; ?>
        </div>
    </form>
</div>
