<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <section class="field">
            <header>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </header>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
        </section>

        <section class="field">
            <header>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </header>
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        </section>

        <section class="field">
            <header>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </header>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        </section>

        <footer>
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </footer>
    </form>
</section>
