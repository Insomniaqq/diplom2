<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Аналитика по поставщикам ({{ $year }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="GET" action="{{ route('reports.suppliers') }}" class="mb-4 flex items-center gap-2">
                    <label for="year">Год:</label>
                    <input type="number" name="year" id="year" value="{{ $year }}" min="2000" max="2100" class="form-input w-24">
                    <button type="submit" class="btn btn-primary">Показать</button>
                </form>
                <div class="flex flex-col items-center" style="margin-bottom: 0.5rem; max-width: 300px;">
                    <div style="width:250px; height:250px; display:flex; align-items:center; justify-content:center;">
                        <canvas id="suppliersChart" width="250" height="250" style="display:block; max-width:250px; max-height:250px;"></canvas>
                    </div>
                    <div class="text-gray-500 text-xs mt-1">Наведите на сегмент, чтобы увидеть детали</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Поставщик</th>
                                <th class="px-4 py-2">Сумма контрактов</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td class="border px-4 py-2">{{ $row['name'] }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['sum'], 2, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('suppliersChart').getContext('2d');
        const data = {
            labels: [@foreach($data as $row) '{!! $row['name'] !!}', @endforeach],
            datasets: [{
                label: 'Сумма контрактов',
                data: [@foreach($data as $row) {{ $row['sum'] }}, @endforeach],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(99, 255, 132, 0.7)',
                    'rgba(192, 75, 192, 0.7)',
                    'rgba(102, 153, 255, 0.7)',
                    'rgba(159, 64, 255, 0.7)'
                ],
                borderWidth: 1
            }]
        };
        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 18,
                            padding: 18,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total ? (value / total * 100).toFixed(1) : 0;
                                return `${label}: ${value.toLocaleString()} ₽ (${percent}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Сумма контрактов по поставщикам',
                        font: { size: 18 }
                    },
                },
                animation: {
                    animateRotate: true,
                    duration: 1200
                }
            },
        };
        new Chart(ctx, config);
    </script>
</x-app-layout> 