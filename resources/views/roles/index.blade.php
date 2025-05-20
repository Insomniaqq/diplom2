@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Роли</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3" style="display:inline-block; margin-bottom:1.2rem; float:right;">Добавить роль</a>
    <div style="clear:both;"></div>
    @if($roles->count())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Пользователей</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->users->count() }}</td>
                        <td>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Редактировать</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить роль?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">Ролей пока нет.</div>
    @endif
</div>
@endsection 