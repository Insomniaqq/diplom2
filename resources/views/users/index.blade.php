@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Пользователи</h1>
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.2em;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:1.2em;">{{ session('error') }}</div>
    @endif
    <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end mb-3" style="gap:0.7em;">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Поиск по имени или email" value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-auto">
            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $filters['email'] ?? '' }}">
        </div>
        <div class="col-auto">
            <select name="role" class="form-control">
                <option value="">Все роли</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @if(($filters['role'] ?? '') == $role) selected @endif>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}" placeholder="С даты">
        </div>
        <div class="col-auto">
            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}" placeholder="По дату">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Фильтровать</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Сбросить</a>
        </div>
        <div class="col-auto">
            <a href="{{ route('users.export', request()->all()) }}" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Экспорт</a>
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
                    @if(Auth::user()->hasRole('admin'))
                        <th>Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        @if(Auth::user()->hasRole('admin'))
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
@endsection 