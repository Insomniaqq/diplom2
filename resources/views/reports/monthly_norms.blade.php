<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Отчет по месячным нормам использования материалов
        </h2>
    </x-slot>

    @php
        \Carbon\Carbon::setLocale('ru');
    @endphp

    <div class="container">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">Отчет по месячным нормам использования материалов</h1>
                
                <form method="GET" action="{{ route('reports.monthly-norms') }}" class="mb-6 flex items-center gap-2">
                    <label for="month">Месяц:</label>
                    <select name="month" id="month" class="form-select w-32">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    <label for="year">Год:</label>
                    <input type="number" name="year" id="year" value="{{ $year }}" min="2000" max="2100" class="form-input w-24">
                    <button type="submit" class="btn btn-primary">Показать</button>
                </form>

                <p class="mb-4 text-gray-600">Статистика за {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</p>

                @if(empty($reportData))
                    <p>Нет данных о нормах использования материалов за текущий месяц.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Отдел</th>
                                    <th class="px-4 py-2">Материал</th>
                                    <th class="px-4 py-2">Месячная норма</th>
                                    <th class="px-4 py-2">Выдано</th>
                                    <th class="px-4 py-2">Осталось</th>
                                    <th class="px-4 py-2">Процент использования</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $data)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $data['department_name'] }}</td>
                                        <td class="border px-4 py-2">{{ $data['material_name'] }}</td>
                                        <td class="border px-4 py-2">{{ $data['monthly_norm'] }}</td>
                                        <td class="border px-4 py-2">{{ $data['distributed'] }}</td>
                                        <td class="border px-4 py-2">{{ $data['remaining'] }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $data['percentage'] }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('reports.budget') }}" class="btn btn-secondary">Назад к отчетам</a> {{-- Пример ссылки назад, возможно потребуется изменить --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 