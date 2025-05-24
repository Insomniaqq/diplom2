<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Просмотр материала: {{ $material->name }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-xl font-semibold mb-2">Информация о материале</h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p><strong>Название:</strong> {{ $material->name }}</p>
                        <p><strong>Код:</strong> {{ $material->code }}</p>
                        <p><strong>Описание:</strong> {{ $material->description ?? 'Нет описания' }}</p>
                    </div>
                    <div>
                        <p><strong>Единица измерения:</strong> {{ $material->unit_of_measure }}</p>
                        <p><strong>Минимальное количество:</strong> {{ $material->min_quantity }}</p>
                        <p><strong>Текущее количество:</strong> {{ $material->current_quantity }}</p>
                        <p><strong>Цена:</strong> {{ $material->price }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('materials.index') }}" class="btn btn-secondary">Назад к списку</a>
                    <a href="{{ route('materials.edit', $material) }}" class="btn btn-warning">Редактировать</a>
                </div>

                <h3 class="text-xl font-semibold mb-2">Месячные нормы по отделам</h3>
                @if($material->departments->isEmpty())
                    <p>Этот материал не привязан ни к одному отделу.</p>
                @else
                    <div class="overflow-x-auto mb-6">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Отдел</th>
                                    <th class="px-4 py-2">Месячная норма</th>
                                    <th class="px-4 py-2">Выдано за месяц</th>
                                    <th class="px-4 py-2">Осталось</th>
                                    <th class="px-4 py-2">Процент использования</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($material->departments as $department)
                                    @php
                                        $monthlyQuantity = $department->pivot->monthly_quantity;
                                        $distributedQuantity = $department->distributions->sum('quantity');
                                        $remaining = max(0, $monthlyQuantity - $distributedQuantity);
                                        $percentage = $monthlyQuantity > 0 ? 
                                            min(100, round(($distributedQuantity / $monthlyQuantity) * 100)) : 0;
                                    @endphp
                                    <tr>
                                        <td class="border px-4 py-2">{{ $department->name }}</td>
                                        <td class="border px-4 py-2">{{ $monthlyQuantity }}</td>
                                        <td class="border px-4 py-2">{{ $distributedQuantity }}</td>
                                        <td class="border px-4 py-2">{{ $remaining }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $percentage }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @push('scripts')
                <script>
                    document.getElementById('distribute-form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const form = e.target;
                        const departmentId = document.getElementById('department_id').value;
                        
                        // Use the correct route with department ID
                        const distributeRoute = "{{ route('material.distribute', ':departmentId') }}";
                        const actionUrl = distributeRoute.replace(':departmentId', departmentId);
                        
                        form.action = actionUrl;
                        form.submit();
                    });
                </script>
                @endpush

                <h3 class="text-xl font-semibold mb-2 mt-6">История распределений</h3>
                @if($material->distributions->isEmpty())
                    <p>Пока нет записей о распределении этого материала.</p>
                @else
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Отдел</th>
                                <th class="px-4 py-2">Количество</th>
                                <th class="px-4 py-2">Дата и время</th>
                                <th class="px-4 py-2">Выдал</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($material->distributions as $distribution)
                                <tr>
                                    <td class="border px-4 py-2">{{ $distribution->department->name ?? 'Отдел удален' }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->quantity }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->created_at->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $distribution->distributor->name ?? 'Пользователь удален' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 