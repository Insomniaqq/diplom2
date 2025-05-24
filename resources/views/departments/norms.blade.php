<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Месячные нормы: {{ $department->name }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">{{ $department->name }}</h1>
                <p class="mb-4 text-gray-600">Статистика за {{ now()->format('F Y') }}</p>

                @if(empty($norms))
                    <p>К этому разделу еще не привязаны материалы.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Материал</th>
                                    <th class="px-4 py-2">Месячная норма</th>
                                    <th class="px-4 py-2">Выдано</th>
                                    <th class="px-4 py-2">Осталось</th>
                                    <th class="px-4 py-2">Процент использования</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($norms as $norm)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $norm['material']->name }}</td>
                                        <td class="border px-4 py-2">{{ $norm['monthly_norm'] }}</td>
                                        <td class="border px-4 py-2">{{ $norm['distributed'] }}</td>
                                        <td class="border px-4 py-2">{{ $norm['remaining'] }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $norm['percentage'] }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $norm['percentage'] }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('departments.show', $department) }}" class="btn btn-secondary">Назад к разделу</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 