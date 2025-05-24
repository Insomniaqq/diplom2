<x-app-layout>
    <div class="container mt-12 flex justify-center items-center" style="min-height:60vh;">
        <div class="profile-card" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(21,101,192,0.10); max-width:480px; margin:auto; padding:2.5rem 2.2rem 2.2rem 2.2rem;">
            <div style="display:flex;align-items:center;gap:1.2em;margin-bottom:1.5rem;">
                <div style="background:#2563eb; color:#fff; border-radius:50%; width:64px; height:64px; display:flex; align-items:center; justify-content:center; font-size:2.2em;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h2 style="font-size:2rem; font-weight:700; color:#2563eb; margin-bottom:0.2em;">Профиль пользователя</h2>
                    <span style="color:#64748b; font-size:1.1em;">{{ Auth::user()->email }}</span>
                </div>
            </div>
            <div style="font-size:1.18em; color:#1e293b;">
                <div style="margin-bottom:0.7em;"><b>Имя:</b> {{ Auth::user()->name }}</div>
                <div style="margin-bottom:0.7em;"><b>Email:</b> {{ Auth::user()->email }}</div>
                <div style="margin-bottom:0.7em;"><b>Роль:</b> 
                    @if(Auth::user()->role === 'Admin')
                        <span style="color:#2563eb;font-weight:600;">Администратор</span>
                    @elseif(Auth::user()->role === 'Manager')
                        <span style="color:#22c55e;font-weight:600;">Менеджер</span>
                    @else
                        <span style="color:#64748b;font-weight:600;">Пользователь</span>
                    @endif
                </div>
                <div style="margin-bottom:0.7em;"><b>Дата регистрации:</b> {{ Auth::user()->created_at->format('d.m.Y H:i') }}</div>
            </div>
        </div>
    </div>
</x-app-layout> 