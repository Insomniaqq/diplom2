<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Редактировать раздел: {{ $department->name }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="form-container">
            <h1>Редактировать раздел</h1>

            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Название раздела:</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $department->name) }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <h3 class="text-xl font-semibold mb-2">Материалы раздела</h3>
                <div id="materials-list">
                    @foreach($department->materials as $material)
                        <div class="flex items-center gap-4 mb-2 material-item">
                            <div class="flex-grow">
                                <label>Материал:</label>
                                <select name="materials[{{ $loop->index }}][material_id]" class="form-select w-full" required>
                                    <option value="">Выберите материал</option>
                                    @foreach($materials as $optionMaterial)
                                        <option value="{{ $optionMaterial->id }}" {{ $material->id == $optionMaterial->id ? 'selected' : '' }}>{{ $optionMaterial->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Месячная норма:</label>
                                <input type="number" name="materials[{{ $loop->index }}][monthly_quantity]" class="form-input w-24" value="{{ old('materials.' . $loop->index . '.monthly_quantity', $material->pivot->monthly_quantity) }}" required min="0">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-material">Удалить</button>
                        </div>
                    @endforeach
                    @if($department->materials->isEmpty())
                         <div class="flex items-center gap-4 mb-2 material-item">
                            <div class="flex-grow">
                                <label>Материал:</label>
                                <select name="materials[0][material_id]" class="form-select w-full" required>
                                    <option value="">Выберите материал</option>
                                    @foreach($materials as $optionMaterial)
                                        <option value="{{ $optionMaterial->id }}">{{ $optionMaterial->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Месячная норма:</label>
                                <input type="number" name="materials[0][monthly_quantity]" class="form-input w-24" value="{{ old('materials.0.monthly_quantity') }}" required min="0">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-material">Удалить</button>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-material" class="btn btn-secondary mb-4">Добавить материал</button>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-material').addEventListener('click', function () {
            const materialsList = document.getElementById('materials-list');
            const index = materialsList.children.length;
            const newItem = document.createElement('div');
            newItem.classList.add('flex', 'items-center', 'gap-4', 'mb-2', 'material-item');
            newItem.innerHTML = `
                <div class="flex-grow">
                    <label>Материал:</label>
                    <select name="materials[${index}][material_id]" class="form-select w-full" required>
                        <option value="">Выберите материал</option>
                        @foreach($materials as $optionMaterial)
                            <option value="{{ $optionMaterial->id }}">{{ $optionMaterial->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Месячная норма:</label>
                    <input type="number" name="materials[${index}][monthly_quantity]" class="form-input w-24" required min="0">
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-material">Удалить</button>
            `;
            materialsList.appendChild(newItem);

            newItem.querySelector('.remove-material').addEventListener('click', function () {
                newItem.remove();
                // Optionally re-index the inputs here if needed on the backend
                // This is not strictly necessary for sync() but might be good practice
            });
        });

        document.querySelectorAll('.remove-material').forEach(button => {
            button.addEventListener('click', function () {
                button.closest('.material-item').remove();
                // Optionally re-index the inputs here if needed on the backend
            });
        });
    </script>
</x-app-layout> 