<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Система управления закупками') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dropdown-anim {
            display: none;
            /* opacity: 0; */
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.22s cubic-bezier(.4,0,.2,1), transform 0.22s cubic-bezier(.4,0,.2,1);
        }
        .dropdown-anim--visible {
            display: block;
            /* opacity: 1; */
            transform: translateY(0);
            pointer-events: auto;
        }
        .notification-dropdown-fixed {
            position: fixed !important;
            top: 18px;
            right: 36px;
            z-index: 99999 !important;
            margin: 0;
            pointer-events: auto !important;
        }
        .notification-dropdown-content {
            pointer-events: auto;
        }
        @media (max-width: 600px) {
            .notification-dropdown-fixed {
                right: 12px;
                top: 12px;
            }
        }
        header, .main-content, .container, .header-slot, .page-header {
            z-index: 1 !important;
            position: relative !important;
        }
    </style>

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased">
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house" style="margin-right:8px;"></i>Главная
                </a>
                
                @if(Auth::user()->role === 'Admin')
                    {{-- Администратор видит все пункты меню --}}
                    <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked" style="margin-right:8px;"></i>Материалы
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-field" style="margin-right:8px;"></i>Поставщики
                    </a>
                    <a href="{{ route('contracts.index') }}" class="{{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-contract" style="margin-right:8px;"></i>Контракты
                    </a>
                    <div class="dropdown" style="display:inline-block;">
                        <a href="#" class="dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}" style="padding-right:18px;">
                            <i class="fa-solid fa-chart-line" style="margin-right:8px;"></i>Аналитика <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-menu" style="display:none;position:absolute;background:#2563eb;box-shadow:0 2px 8px rgba(0,0,0,0.1);z-index:1000;">
                            <a href="{{ route('reports.budget') }}" class="dropdown-item">Бюджет</a>
                            <a href="{{ route('reports.requests') }}" class="dropdown-item">Заявки</a>
                            <a href="{{ route('reports.monthly-norms') }}" class="dropdown-item">Месячные нормы</a>
                            <a href="{{ route('reports.suppliers') }}" class="dropdown-item">Поставщики</a>
                            <a href="{{ route('reports.materials-consumption') }}" class="dropdown-item">Расход материалов</a>
                        </div>
                    </div>
                    <a href="{{ route('purchase-requests.index') }}" class="{{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-signature" style="margin-right:8px;"></i>Заявки на закупку
                    </a>
                    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px;"></i>Заказы
                    </a>
                    <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question" style="margin-right:8px;"></i>Помощь
                    </a>
                    <a href="{{ route('admin.panel') }}" class="{{ request()->routeIs('admin.panel') ? 'active' : '' }}">
                        <i class="fa-solid fa-gears" style="margin-right:8px;"></i>Админ-панель
                    </a>
                @elseif(Auth::user()->role === 'Manager')
                    {{-- Заведующий складом видит все кроме админ-панели --}}
                    <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked" style="margin-right:8px;"></i>Материалы
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-field" style="margin-right:8px;"></i>Поставщики
                    </a>
                    <a href="{{ route('contracts.index') }}" class="{{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-contract" style="margin-right:8px;"></i>Контракты
                    </a>
                    <div class="dropdown" style="display:inline-block;">
                        <a href="#" class="dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}" style="padding-right:18px;">
                            <i class="fa-solid fa-chart-line" style="margin-right:8px;"></i>Аналитика <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-menu" style="display:none;position:absolute;background:#2563eb;box-shadow:0 2px 8px rgba(0,0,0,0.1);z-index:1000;">
                            <a href="{{ route('reports.budget') }}" class="dropdown-item">Бюджет</a>
                            <a href="{{ route('reports.requests') }}" class="dropdown-item">Заявки</a>
                            <a href="{{ route('reports.monthly-norms') }}" class="dropdown-item">Месячные нормы</a>
                            <a href="{{ route('reports.suppliers') }}" class="dropdown-item">Поставщики</a>
                            <a href="{{ route('reports.materials-consumption') }}" class="dropdown-item">Расход материалов</a>
                        </div>
                    </div>
                    <a href="{{ route('purchase-requests.index') }}" class="{{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-signature" style="margin-right:8px;"></i>Заявки на закупку
                    </a>
                    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px;"></i>Заказы
                    </a>
                    <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question" style="margin-right:8px;"></i>Помощь
                    </a>
                @else
                    {{-- Обычный работник склада видит только основные пункты --}}
                    <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked" style="margin-right:8px;"></i>Материалы
                    </a>
                    <a href="{{ route('purchase-requests.index') }}" class="{{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-signature" style="margin-right:8px;"></i>Заявки на закупку
                    </a>
                    <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question" style="margin-right:8px;"></i>Помощь
                    </a>
                @endif
            </div>
            <div class="user-dropdown">
                <button class="user-dropdown-trigger" id="userMenuBtn" aria-haspopup="true" aria-expanded="false" style="display:flex;align-items:center;gap:8px;background:transparent;border:none;outline:none;cursor:pointer;padding:10px 18px 10px 14px;border-radius:12px 12px 0 0;font-weight:600;color:#fff;">
                    <i class="fa-regular fa-user" style="font-size:1.3em;"></i>
                    <span style="font-size:1.08em;">{{ ucfirst(Auth::user()->name) }}</span>
                    <i class="fa-solid fa-chevron-down" style="font-size:1em;"></i>
                </button>
                <div class="user-dropdown-content dropdown-anim" id="userMenuDropdown" style="background:#fff;padding:18px 22px 10px 18px;border-radius:16px;box-shadow:0 2px 16px rgba(21,101,192,0.13);margin-top:2px;min-width:170px;position:absolute;right:0;z-index:1001;">
                    <a href="{{ route('profile.show') }}" style="display:flex;align-items:center;gap:10px;font-size:1.08em;color:#2563eb;font-weight:500;padding:6px 0 10px 0;text-decoration:none;">
                        <i class="fa-regular fa-user" style="font-size:1.15em;"></i>Профиль
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:10px;font-size:1.08em;color:#ef4444;font-weight:500;background:none;border:none;outline:none;cursor:pointer;padding:6px 0 0 0;">
                            <i class="fa-solid fa-arrow-right-to-bracket" style="font-size:1.15em;"></i>Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div>
        @if (isset($header))
            <header class="bg-white shadow" style="margin-left:240px;">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        <main>
            <div class="main-content">
                {{ $slot }}
            </div>
        </main>
    </div>
    @include('components.notifications')
    <script>
        // Выпадающее меню профиля (открытие/закрытие с анимацией)
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('userMenuBtn');
            const dropdown = document.getElementById('userMenuDropdown');
            if(btn && dropdown) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('dropdown-anim--visible');
                });
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target) && e.target !== btn) {
                        dropdown.classList.remove('dropdown-anim--visible');
                    }
                });
            }
        });
        // Простая логика для выпадающего меню аналитики
        document.querySelectorAll('.dropdown-toggle').forEach(function(el) {
            el.addEventListener('mouseover', function() {
                this.nextElementSibling.style.display = 'block';
            });
            el.parentElement.addEventListener('mouseleave', function() {
                el.nextElementSibling.style.display = 'none';
            });
        });
        // Выпадающее меню уведомлений (гарантированная работа)
        document.addEventListener('DOMContentLoaded', function() {
            const notifBtn = document.getElementById('notificationBtnMain');
            const notifDropdown = document.getElementById('notificationDropdownMain');
            if(notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notifDropdown.classList.toggle('dropdown-anim--visible');
                });
                document.addEventListener('click', function(e) {
                    if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
                        notifDropdown.classList.remove('dropdown-anim--visible');
                    }
                });
            }
        });
    </script>
</body>
</html> 