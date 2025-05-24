<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Пользователи
        </h2>
    </x-slot>

<div class="container">
    
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.2em;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:1.2em;">{{ session('error') }}</div>
    @endif

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

    <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end mb-3" style="gap:0.7em;">
        <div class="col-auto form-group">
            <label for="search" class="form-label">Поиск по имени или email</label>
            <input type="text" name="search" id="search" class="form-input" placeholder="Поиск по имени или email" value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-auto form-actions">
            <button type="submit" class="btn btn-primary mr-2">Фильтровать</button>
             <a href="{{ route('users.index') }}" class="btn btn-secondary">Сбросить</a>
        </div>
    </form>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Добавить пользователя</a>
    @if($users->count())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дата регистрации</th>
                    @if(Auth::user()->hasRole('Admin'))
                        <th>Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $roleTranslations = [
                        'Admin' => 'Администратор',
                        'Manager' => 'Зав.склада',
                        'Employee' => 'Работник склада',
                    ];
                @endphp
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $roleTranslations[$user->role] ?? $user->role }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        @if(Auth::user()->hasRole('Admin'))
                        <td>
                            <a href="{{ route('users.change-password-form', $user->id) }}" class="btn btn-sm btn-warning">Сменить пароль</a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $users->links() }}</div>
    @else
        <div class="alert alert-info">Список пользователей пока пуст.</div>
    @endif
</div>
</x-app-layout> 