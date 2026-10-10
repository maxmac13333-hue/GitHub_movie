<x-guest-layout>
 <div class="auth-heading"><span class="auth-symbol">✦</span><span class="eyebrow">YOUR MOVIE JOURNEY STARTS HERE</span><h1>ยินดีต้อนรับสู่ FF</h1><p>สร้างบัญชี แล้วไปค้นหาหนังเรื่องโปรดกัน</p></div>
 <form method="POST" action="{{ route('register') }}" class="auth-fields">@csrf
  <div><label for="name">ชื่อที่ใช้แสดง</label><input id="name" name="name" value="{{ old('name') }}" placeholder="ชื่อของคุณ" required autofocus autocomplete="name"><x-input-error :messages="$errors->get('name')" /></div>
  <div><label for="email">อีเมล</label><input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username"><x-input-error :messages="$errors->get('email')" /></div>
  <div><label for="password">รหัสผ่าน</label><input id="password" name="password" type="password" placeholder="อย่างน้อย 8 ตัวอักษร" minlength="8" required autocomplete="new-password"><x-input-error :messages="$errors->get('password')" /></div>
  <div><label for="password_confirmation">ยืนยันรหัสผ่าน</label><input id="password_confirmation" name="password_confirmation" type="password" placeholder="กรอกรหัสผ่านอีกครั้ง" required autocomplete="new-password"></div>
  <button class="btn auth-submit">สมัครสมาชิก <span>→</span></button>
 </form><p class="auth-signin">มีบัญชีอยู่แล้ว? <a class="text-link" href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
</x-guest-layout>

