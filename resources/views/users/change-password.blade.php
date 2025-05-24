<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Смена пароля для пользователя
        </h2>
    </x-slot>

<div class="container" style="max-width:480px; margin:auto;">
    
    <div class="card p-4">

        <style>
            .form-group {
                margin-bottom: 1rem;
            }

            .form-group:last-of-type {
                margin-bottom: 0.5rem;
            }

            .form-label {
                display: block;
                font-weight: bold;
                margin-bottom: 0.5rem;
                color: #4a5568; /* gray-700 */
                font-size: 0.875rem; /* sm */
            }

            .form-input, .form-select {
                display: block;
                width: 100%;
                max-width: 400px; /* Added max-width */
                padding: 0.5rem 0.75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .form-input:focus, .form-select:focus {
                border-color: #80bdff;
                outline: 0;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .btn {
                display: inline-block;
                font-weight: 400;
                color: #212529;
                text-align: center;
                vertical-align: middle;
                cursor: pointer;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: 0.25rem;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .btn-primary {
                color: #fff;
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-primary:hover {
                color: #fff;
                background-color: #0056b3;
                border-color: #0056b3;
            }

             .btn-secondary {
                 color: #fff;
                 background-color: #6c757d;
                 border-color: #6c757d;
             }

             .btn-secondary:hover {
                 color: #fff;
                 background-color: #545b62;
                 border-color: #545b62;
             }

            .form-actions {
                display: flex;
                justify-content: flex-start;
                margin-top: 1rem;
            }

            .mr-2 {
                margin-right: 0.5rem;
            }
        </style>

        <form method="POST" action="{{ route('users.change-password', $user->id) }}">
            @csrf
            <div class="form-group">
                <label for="password" class="form-label">Новый пароль</label>
                <input type="password" class="form-input" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary mr-2">Сохранить</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout> 