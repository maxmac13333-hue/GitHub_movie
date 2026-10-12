<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title>@yield('title', 'ผู้ดูแลระบบ') · FF Cinema</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $pageStyles = match (true) {
            request()->routeIs('admin.movies') => 'resources/css/pages/admin-movies.css',
            request()->routeIs('admin.showtimes') => 'resources/css/pages/admin-showtimes.css',
            default => null,
        };
    @endphp
    @if ($pageStyles)
        @vite($pageStyles)
    @endif
</head>

<body
    class="customer-page admin-page"
    x-data="{ menu: false }"
>
    <aside
        class="admin-sidebar"
        :class="{ 'is-open': menu }"
    >
        <a
            class="brand"
            href="{{ route('admin.movies') }}"
        >
            <span class="brand-mark">FF<span>✦</span></span>
            <span>FF<span class="brand-light">CINEMA</span></span>
        </a>

        <p class="admin-caption">WORKSPACE / ผู้ดูแลระบบ</p>

        <nav aria-label="เมนูจัดการระบบ">
            @foreach ([
        'movies' => ['▣', 'ภาพยนตร์'],
        'showtimes' => ['◷', 'รอบฉาย'],
        'theaters' => ['▤', 'โรงและที่นั่ง'],
        'bookings' => ['▧', 'รายการจอง'],
        'users' => ['◎', 'ผู้ใช้งาน'],
    ] as $key => $item)
                <a
                    href="{{ route('admin.' . $key) }}"
                    class="{{ request()->routeIs('admin.' . $key . '*') ? 'active' : '' }}"
                >
                    <span>{{ $item[0] }}</span>
                    {{ $item[1] }}
                </a>
            @endforeach
        </nav>

        <div class="admin-side-bottom">
            <span class="admin-badge">ADMINISTRATOR</span>
            <p>จัดการทุกเรื่องราว<br>ก่อนหนังเริ่มฉาย</p>
            <a href="{{ route('movies.index') }}">ดูเว็บไซต์ฝั่งผู้ใช้ ↗</a>
        </div>
    </aside>

    <div class="admin-workspace">
        <header class="admin-topbar">
            <button
                class="admin-toggle"
                @click="menu = !menu"
                aria-label="เปิดเมนูผู้ดูแล"
                :aria-expanded="menu"
            >☰</button>
            <span>ระบบจัดการโรงภาพยนตร์</span>
            <div>
                <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                <strong>{{ auth()->user()->name }}</strong>
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf
                    <button>ออกจากระบบ</button>
                </form>
            </div>
        </header>

        <main class="admin-main">
            <div class="admin-page-heading">
                <span class="eyebrow">FF CINEMA MANAGEMENT</span>
                @yield('header')
                <p>จัดการข้อมูลและตรวจสอบความพร้อมของโรงภาพยนตร์</p>
            </div>

            @if (session('status'))
                <div
                    class="notice success"
                    role="status"
                >{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div
                    class="notice error"
                    role="alert"
                >
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="admin-footer">FF Cinema · พื้นที่สำหรับผู้ดูแลระบบ</footer>
    </div>
</body>

</html>
