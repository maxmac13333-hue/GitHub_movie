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
    <title>@yield('title', 'FF Cinema') · FF Cinema</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $pageStyles = match (true) {
            request()->routeIs('movies.index') => 'resources/css/pages/movies.css',
            request()->routeIs('movies.showtimes') => 'resources/css/pages/showtimes.css',
            request()->routeIs('showtimes.seats') => 'resources/css/pages/select-seats.css',
            request()->routeIs('bookings.checkout') => 'resources/css/pages/checkout.css',
            request()->routeIs('booking-history.index') => 'resources/css/pages/booking-history.css',
            request()->routeIs('bookings.show', 'bookings.slip') => 'resources/css/pages/booking.css',
            default => null,
        };
    @endphp
    @if ($pageStyles)
        @vite($pageStyles)
    @endif
</head>

<body class="customer-page">
    <header
        class="site-nav"
        x-data="{ open: false }"
    >
        <div class="wrap nav-inner">
            <a
                class="brand"
                href="{{ route('movies.index') }}"
                aria-label="FF Cinema หน้าแรก"
            >
                <span class="brand-mark">FF<span>✦</span></span>
                <span>FF<span class="brand-light">CINEMA</span></span>
            </a>

            <nav
                class="nav-links"
                aria-label="เมนูหลัก"
            >
                <a
                    class="{{ request()->routeIs('dashboard', 'movies.*') ? 'active' : '' }}"
                    href="{{ route('movies.index') }}"
                >ภาพยนตร์</a>
                <a
                    class="{{ request()->routeIs('booking-history.*', 'bookings.*') ? 'active' : '' }}"
                    href="{{ route('booking-history.index') }}"
                >การจองของฉัน</a>
            </nav>

            <div class="nav-account">
                @auth
                    @if (auth()->user()->is_admin)
                        <a
                            class="admin-link"
                            href="{{ route('admin.movies') }}"
                        >จัดการระบบ ↗</a>
                    @endif

                    <a
                        href="{{ route('profile.edit') }}"
                        class="profile-link"
                    >
                        <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </a>

                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf
                        <button class="logout-link">ออกจากระบบ</button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="btn btn-small"
                    >เข้าสู่ระบบ <span>↗</span></a>
                @endauth
            </div>

            <button
                type="button"
                class="mobile-menu"
                @click="open = !open"
                :aria-expanded="open"
                aria-label="เมนู"
            >☰</button>
        </div>

        <nav
            x-show="open"
            x-cloak
            class="mobile-nav"
        >
            <a href="{{ route('movies.index') }}">ภาพยนตร์</a>
            <a href="{{ route('booking-history.index') }}">การจองของฉัน</a>
            @auth
                <a href="{{ route('profile.edit') }}">โปรไฟล์</a>
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf
                    <button>ออกจากระบบ</button>
                </form>
            @endauth
        </nav>
    </header>

    <main>
        @if (session('status'))
            <div class="wrap">
                <div
                    class="notice success"
                    role="status"
                >{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="wrap">
                <div
                    class="notice error"
                    role="alert"
                >
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @if (isset($header))
            <div class="wrap page-heading">{{ $header }}</div>
        @elseif ($__env->hasSection('header'))
            <div class="wrap page-heading">@yield('header')</div>
        @endif

        @if ($__env->hasSection('content'))
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <footer class="site-footer">
        <div class="wrap footer-inner">
            <a
                class="brand"
                href="{{ route('movies.index') }}"
            >
                <span class="brand-mark">FF<span>✦</span></span>
                <span>FF<span class="brand-light">CINEMA</span></span>
            </a>
            <p>ทุกเรื่องราว เริ่มต้นที่ที่นั่งของคุณ</p>
            <span>© {{ date('Y') }} FF Cinema</span>
        </div>
    </footer>
</body>

</html>
