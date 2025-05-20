<div class="notification-dropdown notification-dropdown-fixed">
    @auth
        @php
            $unread = Auth::user()->unreadNotifications;
        @endphp
        <button class="notification-btn" id="notificationBtnMain" aria-haspopup="true" aria-expanded="false" style="background:transparent;border:none;outline:none;cursor:pointer;position:relative;padding:10px 14px;">
            <i class="fa-regular fa-bell" style="font-size:1.35em;color:#2563eb;"></i>
            @if($unread->count())
                <span style="position:absolute;top:2px;right:10px;background:#ef4444;color:#fff;border-radius:50%;font-size:0.85em;padding:2px 6px;min-width:18px;text-align:center;">{{ $unread->count() }}</span>
            @endif
        </button>
        <div class="notification-dropdown-content dropdown-anim" id="notificationDropdownMain" style="background:#fff;padding:14px 0 8px 0;border-radius:14px;box-shadow:0 2px 16px rgba(21,101,192,0.13);min-width:320px;position:absolute;right:0;z-index:1002;margin-top:12px;max-width:95vw;">
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