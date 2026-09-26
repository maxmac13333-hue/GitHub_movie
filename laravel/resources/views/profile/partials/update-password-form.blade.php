<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-medium text-sm text-gray-700">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="current-password">
            @foreach ($errors->updatePassword->get('current_password') as $message)
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @endforeach
        </div>

        <div>
            <label for="update_password_password" class="block font-medium text-sm text-gray-700">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password">
            @foreach ($errors->updatePassword->get('password') as $message)
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @endforeach
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password">
            @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @endforeach
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
