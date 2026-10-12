<x-guest-layout>
    <div class="auth-heading"><span class="auth-symbol">✦</span><span class="eyebrow">WELCOME BACK</span>
        <h1>กลับมาดูหนังกัน</h1>
        <p>เข้าสู่ระบบเพื่อจองที่นั่งและดูบัตรของคุณ</p>
    </div>
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />
    <form
        method="POST"
        action="{{ route('login') }}"
        class="auth-fields"
        x-data="{ show: false }"
    >@csrf
        <div><label for="email">อีเมล</label><input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required
                autofocus
                autocomplete="username"
            ><x-input-error :messages="$errors->get('email')" /></div>
        <div><label for="password">รหัสผ่าน</label>
            <div class="password-field"><input
                    id="password"
                    name="password"
                    :type="show ? 'text' : 'password'"
                    type="password"
                    placeholder="กรอกรหัสผ่านของคุณ"
                    required
                    autocomplete="current-password"
                ><button
                    type="button"
                    @click="show=!show"
                    :aria-label="show ? 'ซ่อนรหัสผ่าน' : 'แสดงรหัสผ่าน'"
                    x-text="show?'ซ่อน':'แสดง'"
                >แสดง</button></div><x-input-error :messages="$errors->get('password')" />
        </div>
        <div class="auth-options"><label class="remember-label"><input
                    type="checkbox"
                    name="remember"
                >
                จดจำฉันไว้</label><a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a></div>
        <button class="btn auth-submit">เข้าสู่ระบบ <span>→</span></button>
    </form>
    <div class="auth-divider"><span>เพิ่งมาที่ FF Cinema?</span></div><a
        class="btn btn-outline auth-submit"
        href="{{ route('register') }}"
    >สร้างบัญชีใหม่</a>
</x-guest-layout>
