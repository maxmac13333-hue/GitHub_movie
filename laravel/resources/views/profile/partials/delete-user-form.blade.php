<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" onclick="document.getElementById('confirm-user-deletion').showModal()" class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-red-500 focus:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
        {{ __('Delete Account') }}
    </button>

    <dialog id="confirm-user-deletion" class="m-auto w-[calc(100%-2rem)] max-w-lg rounded-lg bg-white p-0 shadow-xl backdrop:bg-gray-900/50" onclick="if (event.target === this) this.close()">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="mt-1 block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('Password') }}" autocomplete="current-password">
                @foreach ($errors->userDeletion->get('password') as $message)
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" onclick="document.getElementById('confirm-user-deletion').close()" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="ms-3 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-red-500 focus:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </dialog>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.getElementById('confirm-user-deletion').showModal();
        </script>
    @endif
</section>
