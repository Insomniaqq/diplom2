<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Просмотр раздела: {{ $department->name }}
        </h2>
    </x-slot>

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

                <form action="{{ route('material.distribute', $department) }}" method="POST">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <div class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <label for="material_id" class="block text-sm font-medium text-gray-700">Материал</label>
                            <select name="material_id" id="material_id" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($department->materials as $materialOption)
                                    <option value="{{ $materialOption->id }}">{{ $materialOption->name }} (на складе: {{ $materialOption->current_quantity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Количество</label>
                            <input type="number" name="quantity" id="quantity" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Выдать</button>
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
                    <a href="{{ route('departments.norms', $department) }}" class="btn btn-info">Месячные нормы</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 