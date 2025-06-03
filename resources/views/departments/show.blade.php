<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Просмотр раздела: {{ $department->name }}
        </h2>
    </x-slot>

    <style>
        /* Стили для меток */
        .material-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        /* Стили для выпадающего списка */
        .material-form select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Включаем padding и border в общую ширину */
            font-size: 1rem;
        }

        /* Стили для кнопки */
        .material-form button[type="submit"] {
            padding: 8px 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #eee;
            cursor: pointer;
            font-size: 1rem;
        }

        .material-form button[type="submit"]:hover {
            background-color: #ddd;
        }
    </style>

    <div class="container">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">{{ $department->name }}</h1>
                <p class="mb-4"><strong>Описание:</strong> {{ $department->description ?? 'Нет описания' }}</p>

                <h3 class="text-xl font-semibold mb-2">Используемые материалы</h3>
                @if($department->materials->isEmpty())
                    <p>К этому разделу еще не привязаны материалы.</p>
                @else
                    <table class="table-auto w-full mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Материал</th>
                                <th class="px-4 py-2">Месячная норма</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->materials as $material)
                                <tr>
                                    <td class="border px-4 py-2">{{ $material->name }}</td>
                                    <td class="border px-4 py-2">{{ $material->pivot->monthly_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <h3 class="text-xl font-semibold mb-2 mt-6">Выдать материал разделу</h3>

                <form action="{{ route('material.distribute', $department) }}" method="POST" class="mt-4 p-6 bg-gray-50 rounded-lg shadow material-form">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="col-span-1 md:col-span-2">
                            <label for="material_id">Материал</label>
                            <select name="material_id" id="material_id" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2">
                                @foreach($department->materials as $materialOption)
                                    <option value="{{ $materialOption->id }}">{{ $materialOption->name }} (на складе: {{ $materialOption->current_quantity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-1">
                            <label for="quantity">Количество</label>
                            <input type="number" name="quantity" id="quantity" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2" min="1" required>
                        </div>
                        <div class="col-span-full flex justify-end">
                            <button type="submit">Выдать</button>
                        </div>
                    </div>
                </form>

                <h3 class="text-xl font-semibold mb-2 mt-6">История распределений</h3>
                @if($department->distributions->isEmpty())
                    <p>Пока нет записей о распределении материалов для этого раздела.</p>
                @else
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Материал</th>
                                <th class="px-4 py-2">Количество</th>
                                <th class="px-4 py-2">Дата и время</th>
                                <th class="px-4 py-2">Выдал</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->distributions as $distribution)
                                <tr>
                                    <td class="border px-4 py-2">{{ $distribution->material->name ?? 'Материал удален' }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->quantity }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->created_at->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->distributor->name ?? 'Пользователь удален' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="mt-6">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Назад к списку</a>
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">Редактировать</a>
                    @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager'))
                        <a href="{{ route('departments.norms', $department) }}" class="btn btn-info">Месячные нормы</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 