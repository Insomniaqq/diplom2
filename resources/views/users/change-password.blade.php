@extends('layouts.app')

@section('content')
<div class="container" style="max-width:480px; margin:auto;">
    <h1 class="mb-4">Смена пароля для пользователя</h1>
    <div class="card p-4">
        <form method="POST" action="{{ route('users.change-password', $user->id) }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Новый пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-success">Сохранить</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection 