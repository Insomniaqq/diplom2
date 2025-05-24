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

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dropdown-anim {
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.22s cubic-bezier(.4,0,.2,1), transform 0.22s cubic-bezier(.4,0,.2,1);
        }
        .dropdown-anim--visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .notification-dropdown-fixed {
            position: fixed !important;
            top: 18px;
            right: 24px;
            z-index: 2000;
            margin: 0;
        }
        .notification-dropdown-content {
            right: 0 !important;
            left: auto !important;
            margin-top: 12px !important;
            min-width: 320px;
            max-width: 95vw;
        }
        @media (max-width: 500px) {
            .notification-dropdown-content {
                min-width: 220px;
                font-size: 0.97em;
            }
        }
        .notification-btn span {
            top: 2px !important;
        }
    </style>
    <style>
        .status-badge {
            display: inline-block;
            padding: 0.25em 0.5em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
        }

        /* Purchase Request and Order Statuses */
        .status-pending { background-color: #ffc107; /* Yellow */ }
        .status-approved { background-color: #28a745; /* Green */ }
        .status-rejected { background-color: #dc3545; /* Red */ }
        .status-completed { background-color: #28aa45; /* Darker Green */ }
        .status-confirmed { background-color: #17a2b8; /* Cyan */ }
        .status-shipped { background-color: #007bff; /* Blue */ }
        .status-delivered { background-color: #28a745; /* Green */ }
        .status-archived { background-color: #6c757d; /* Gray */ }
        .status-unarchived { background-color: #ffc107; /* Yellow */ }

        /* Contract Statuses */
        .status-active { background-color: #28a745; /* Green */ }
        .status-closed { background-color: #6c757d; /* Gray */ }
        .status-cancelled { background-color: #dc3545; /* Red */ }

        /* Material Statuses (based on quantity) */
        /* Assuming status-rejected for low quantity and status-approved for sufficient quantity */
    </style>
</head>
<body class="font-sans antialiased">
    
    <div class="notification-dropdown notification-dropdown-fixed">
        @auth
            @php
                $unread = Auth::user()->unreadNotifications;
            @endphp
            <button class="notification-btn" id="notificationBtn" aria-haspopup="true" aria-expanded="false" style="background:transparent;border:none;outline:none;cursor:pointer;position:relative;padding:10px 14px;">
                <i class="fa-regular fa-bell" style="font-size:1.35em;color:#2563eb;"></i>
                @if($unread->count())
                    <span style="position:absolute;top:2px;right:10px;background:#ef4444;color:#fff;border-radius:50%;font-size:0.85em;padding:2px 6px;min-width:18px;text-align:center;">{{ $unread->count() }}</span>
                @endif
            </button>
            <div class="notification-dropdown-content dropdown-anim" id="notificationDropdown" style="background:#fff;padding:14px 0 8px 0;border-radius:14px;box-shadow:0 2px 16px rgba(21,101,192,0.13);min-width:320px;position:absolute;right:0;z-index:1002;margin-top:12px;max-width:95vw;">
                @if($unread->count())
                    <form method="POST" action="{{ route('notifications.readAll') }}" style="margin:0 0 8px 0; text-align:right;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#2563eb;font-weight:500;cursor:pointer;font-size:0.98em;">
                            Отметить всё как прочитанные
                        </button>
                    </form>
                @endif
                <div style="max-height:320px;overflow-y:auto;">
                    @forelse($unread->take(7) as $notification)
                        <a href="{{ $notification->data['link'] ?? '#' }}" style="display:block;padding:8px 18px 6px 18px;color:#222;text-decoration:none;font-weight:500;">
                            {{ $notification->data['message'] }}
                            <br><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                    @empty
                        <span style="display:block;padding:10px 18px;color:#888;">Нет новых уведомлений</span>
                    @endforelse
                </div>
                <div style="border-top:1px solid #eee;padding:6px 18px 0 18px;text-align:right;">
                    <a href="{{ route('notifications.index') }}" style="color:#2563eb;font-weight:500;font-size:0.98em;">Показать все</a>
                </div>
            </div>
        @else
            <button class="notification-btn" style="background:transparent;border:none;outline:none;cursor:pointer;position:relative;padding:10px 14px;" disabled>
                <i class="fa-regular fa-bell" style="font-size:1.35em;color:#2563eb;opacity:0.5;"></i>
            </button>
        @endauth
    </div>
    <div class="content-wrapper">
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
    
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
        // Выпадающее меню уведомлений
        document.addEventListener('DOMContentLoaded', function() {
            const notifBtn = document.getElementById('notificationBtn');
            const notifDropdown = document.getElementById('notificationDropdown');
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