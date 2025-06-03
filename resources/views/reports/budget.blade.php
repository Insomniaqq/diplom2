<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Аналитика бюджета ({{ $year }})
            </h2>
            <a href="{{ route('budgets.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-table"></i> К списку бюджетов
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Аналитика по бюджету</h1>
                    <div class="space-x-2">
                        <a href="{{ route('reports.budget.download', ['format' => 'pdf']) }}" 
                           style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                           class="bg-red-600 hover:bg-red-700 text-white font-bold rounded inline-flex items-center">
                            <svg style="width: 12px; height: 12px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Скачать PDF
                        </a>
                        <a href="{{ route('reports.budget.download', ['format' => 'docx']) }}" 
                           style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded inline-flex items-center">
                            <svg style="width: 12px; height: 12px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Скачать DOCX
                        </a>
                    </div>
                </div>
                <form method="GET" action="{{ route('reports.budget') }}" class="mb-4 flex items-center gap-2">
                    <label for="year">Год:</label>
                    <input type="number" name="year" id="year" value="{{ $year }}" min="2000" max="2100" class="form-input w-24">
                    <button type="submit" class="btn btn-primary">Показать</button>
                </form>
                <canvas id="budgetChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        const months = [
            'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
            'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
        ];
        const data = {
            labels: months,
            datasets: [
                {
                    label: 'План',
                    data: [@foreach($months as $m) {{ $amounts[$m] }}, @endforeach],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Факт',
                    data: [@foreach($months as $m) {{ $spents[$m] }}, @endforeach],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }
            ]
        };
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Исполнение бюджета по месяцам'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };
        new Chart(ctx, config);
    </script>
</x-app-layout> 