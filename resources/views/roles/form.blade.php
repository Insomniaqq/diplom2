<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($role) ? 'Редактировать роль' : 'Добавить роль' }}
        </h2>
    </x-slot>

<div class="container">
    
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
</x-app-layout> 