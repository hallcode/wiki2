<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p>Your user information. This cannot be changed.</p>
    </header>

    <form>

        <section class="field">
            <header>
                <label>Username</label>
            </header>
            <p>
                {{ $user->username }}
            </p>
        </section>

        <section class="field">
            <header>
                <label>Email address</label>
            </header>
            <p>
                {{ $user->email }}
            </p>
        </section>

    </form>
</section>
