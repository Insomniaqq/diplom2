<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Редактировать раздел: {{ $department->name }}
        </h2>
    </x-slot>

    <style>
        /* Стили для контейнера формы */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Стили для групп формы */
        .form-group {
            margin-bottom: 20px;
        }

        /* Стили для меток */
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        /* Стили для полей ввода текста и textarea */
        .form-input, .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .form-textarea {
            min-height: 100px; /* Минимальная высота для поля описания */
            resize: vertical; /* Разрешить изменение размера только по вертикали */
        }

        /* Стили для сообщений об ошибках */
        .text-danger {
            color: #e3342f;
            font-size: 0.875em;
            margin-top: 5px;
            display: block;
        }

        /* Стили для заголовков материалов */
        .materials-list h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Стили для элементов материалов */
        .material-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .material-item label {
             font-weight: normal; /* Метки внутри элементов материалов не такие жирные */
             margin-bottom: 4px;
        }

        .material-item .form-select, 
        .material-item .form-input {
             padding: 6px 10px;
             font-size: 0.9rem;
        }

        /* Стили для кнопки удаления материала */
        .remove-material {
            align-self: center; /* Выравнивание кнопки по центру по вертикали */
        }

        /* Стили для кнопки добавления материала */
        #add-material {
            margin-top: 15px;
        }

        /* Стили для действий формы (кнопки сохранения и отмены) */
        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px; /* Расстояние между кнопками */
        }

        /* Базовые стили для кнопок */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

         .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
            border-radius: 3px;
        }

        /* Адаптивность */
        @media (max-width: 600px) {
            .form-actions {
                flex-direction: column;
            }
        }

    </style>

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