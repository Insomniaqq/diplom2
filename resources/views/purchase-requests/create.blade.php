<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Создать заявку на закупку
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 1rem;">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('purchase-requests.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="material_id" class="form-label">Материал</label>
                            <select name="material_id" id="material_id" class="form-control" required>
                                <option value="">Выберите материал</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" @if(old('material_id') == $material->id) selected @endif>{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Количество</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required min="1" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="justification" class="form-label">Обоснование</label>
                            <textarea name="justification" id="justification" class="form-control" required>{{ old('justification') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Создать</button>
                        <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">Отмена</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 