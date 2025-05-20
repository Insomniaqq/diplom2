@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($role) ? 'Редактировать роль' : 'Добавить роль' }}</h1>
    <form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
        @csrf
        @if(isset($role))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $role->description ?? '') }}">
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection 