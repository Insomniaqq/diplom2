<x-guest-layout>
    <div class="login-block" style="width:100%; max-width:420px; background:linear-gradient(135deg,#e3f0ff 0%,#f8fafc 100%); box-shadow:0 4px 24px rgba(21,101,192,0.10); border-radius:1.2rem; padding:2.7rem 2.2rem 2.2rem 2.2rem;">
        <div style="text-align:center; margin-bottom:2.2rem;">
            <span style="display:inline-block; background:#2563eb; color:#fff; border-radius:50%; width:3.5rem; height:3.5rem; line-height:3.5rem; font-size:2rem; margin-bottom:0.7rem;">
                <i class="fa-solid fa-user-lock"></i>
            </span>
            <h2 style="font-size:30px; font-weight:700; color:#2563eb; margin-bottom:0.2em; text-align: center">Система управления закупками расходных материалов</h2>
            <p style="color:#64748b; font-size:1.08em;">Пожалуйста, введите свои данные для авторизации</p>
        </div>
        <!-- Session Status -->
        @if (session('status'))
            @php
                $statusMessage = session('status');
                if ($statusMessage === 'auth.failed') {
                    $statusMessage = 'Неверный email или пароль.';
                }
            @endphp
            <div class="mb-4" style="color: #dc2626;">
                {!! $statusMessage !!}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
            @csrf
            <!-- Email Address -->
            <div style="margin-bottom:1.3rem;">
                <label for="email" class="form-label" style="font-weight:600; color:#2563eb;">Email</label>
                <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" style="font-size:1.13em; padding:0.9rem 1.1rem; border:1.5px solid #c7d7f5; border-radius:0.7rem; margin-top:0.3em;">
                @error('email')
                    <div style="color: #dc2626; font-size: 16px; margin-top: 0.5rem;">
                        {{ $message === 'The email field is required.' ? 'Поле email обязательно для заполнения.' :
                           ($message === 'The email must be a valid email address.' ? 'Пожалуйста, введите корректный email адрес.' : $message) }}
                    </div>
                @enderror
            </div>
            <!-- Password -->
            <div style="margin-bottom:1.3rem;">
                <label for="password" class="form-label" style="font-weight:600; color:#2563eb;">Пароль</label>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" style="font-size:1.13em; padding:0.9rem 1.1rem; border:1.5px solid #c7d7f5; border-radius:0.7rem; margin-top:0.3em;">
                @error('password')
                    <div style="color: #dc2626; font-size: 16px; margin-top: 0.5rem;">
                        {{ $message === 'The password field is required.' ? 'Поле пароль обязательно для заполнения.' :
                           ($message === 'The provided credentials are incorrect.' ? 'Неверный email или пароль.' : $message) }}
                    </div>
                @enderror
            </div>
            <!-- Remember Me -->
            <div class="block" style="margin-bottom:1.5rem;">
                <label for="remember_me" class="inline-flex items-center" style="font-size:1.05em; color:#2563eb;">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember" style="margin-right:0.5em;">
                    Запомнить меня
                </label>
            </div>
            <div style="text-align:center;">
                <button type="submit" class="btn btn-primary" style="width:100%; font-size:1.18em; padding:1rem 0; border-radius:0.7rem;">
                    <i class="fa-solid fa-arrow-right-to-bracket" style="margin-right:0.7em;"></i>Войти
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
