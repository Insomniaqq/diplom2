<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Аналитика по расходу материалов ({{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }})
        </h2>
    </x-slot>

    @php
        \Carbon\Carbon::setLocale('ru');
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Расход и поступление материалов по дням</h1>

                <form method="GET" action="{{ route('reports.materials-consumption') }}" class="mb-6 flex items-center gap-2">
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

                <canvas id="consumptionArrivalChart" height="100"></canvas>

                <h2 class="text-xl font-bold mt-8 mb-4">Расход материалов по разделам за {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h2>

                @if($consumptionData->isEmpty())
                    <p>Нет данных о расходе материалов по разделам за выбранный месяц.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Раздел</th>
                                    <th class="px-4 py-2 text-left">Расход материалов (в единицах)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumptionData as $data)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $data['name'] }}</td>
                                        <td class="border px-4 py-2">{{ $data['consumption'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('consumptionArrivalChart').getContext('2d');
        const dates = @json($dates);
        const distributions = @json($distributions);
        const arrivals = @json($arrivals);

        const data = {
            labels: dates,
            datasets: [
                {
                    label: 'Расход',
                    data: distributions,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                 {
                    label: 'Поступление',
                    data: arrivals,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }
            ]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                 responsive: true,
                 plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Расход и поступление материалов по дням'
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                             display: true,
                             text: 'День месяца'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Количество'
                        }
                    }
                }
            },
        };

        const consumptionArrivalChart = new Chart(ctx, config);
    </script>
</x-app-layout> 