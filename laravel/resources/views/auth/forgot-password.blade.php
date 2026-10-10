<x-guest-layout>
    <h1 class="mb-3 text-2xl font-bold">ลืมรหัสผ่าน</h1>
    <p class="mb-5 text-sm text-gray-600">กรอกอีเมลเพื่อรับลิงก์ตั้งรหัสผ่านใหม่</p>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('password.email') }}">@csrf
        <x-input-label for="email" value="อีเมล" />
        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <x-primary-button class="mt-5 w-full justify-center">ส่งลิงก์ตั้งรหัสผ่าน</x-primary-button>
    </form>
    <a class="mt-4 block text-center text-sm text-indigo-600" href="{{ route('login') }}">กลับไปเข้าสู่ระบบ</a>
</x-guest-layout>
