<x-guest-layout>
    <h1 class="mb-5 text-2xl font-bold">ตั้งรหัสผ่านใหม่</h1>
    <form
        method="POST"
        action="{{ route('password.store') }}"
    >@csrf
        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        >
        <x-input-label
            for="email"
            value="อีเมล"
        />
        <x-text-input
            id="email"
            class="mt-1 block w-full"
            type="email"
            name="email"
            :value="old('email', $request->email)"
            required
        />
        <x-input-error
            :messages="$errors->get('email')"
            class="mt-2"
        />
        <div class="mt-4"><x-input-label
                for="password"
                value="รหัสผ่านใหม่"
            /><x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
            /><x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            /></div>
        <div class="mt-4"><x-input-label
                for="password_confirmation"
                value="ยืนยันรหัสผ่าน"
            /><x-text-input
                id="password_confirmation"
                class="mt-1 block w-full"
                type="password"
                name="password_confirmation"
                required
            /></div>
        <x-primary-button class="mt-5 w-full justify-center">บันทึกรหัสผ่านใหม่</x-primary-button>
    </form>
</x-guest-layout>
