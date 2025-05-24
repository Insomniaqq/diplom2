<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Создать новый раздел
        </h2>
    </x-slot>

    <div class="container">
        <div class="form-container">
            <h1>Создать новый раздел</h1>

            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Название раздела:</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 