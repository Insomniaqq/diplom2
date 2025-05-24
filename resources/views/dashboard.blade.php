<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Главная панель
        </h2>
    </x-slot>

    <div class="container">
        <div class="dashboard-grid">
            <!-- Материалы -->
            <div class="dashboard-card">
                <div class="dashboard-icon bg-blue-100 text-blue-600"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div>
                    <div class="dashboard-title">Материалы</div>
                    <div class="dashboard-value">{{ \App\Models\Material::count() }}</div>
                    <div class="dashboard-desc">Всего материалов</div>
                </div>
            </div>
            <!-- Поставщики -->
            <div class="dashboard-card">
                <div class="dashboard-icon bg-green-100 text-green-600"><i class="fa-solid fa-truck-field"></i></div>
                <div>
                    <div class="dashboard-title">Поставщики</div>
                    <div class="dashboard-value">{{ \App\Models\Supplier::count() }}</div>
                    <div class="dashboard-desc">Активных поставщиков</div>
                </div>
            </div>
            <!-- Заявки на закупку -->
            <div class="dashboard-card">
                <div class="dashboard-icon bg-yellow-100 text-yellow-600"><i class="fa-solid fa-file-signature"></i></div>
                <div>
                    <div class="dashboard-title">Заявки на закупку</div>
                    <div class="dashboard-value">{{ \App\Models\PurchaseRequest::where('status', 'pending')->count() }}</div>
                    <div class="dashboard-desc">Ожидают рассмотрения</div>
                </div>
            </div>
            <!-- Заказы -->
            <div class="dashboard-card">
                <div class="dashboard-icon bg-purple-100 text-purple-600"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div>
                    <div class="dashboard-title">Заказы</div>
                    <div class="dashboard-value">{{ \App\Models\Order::count() }}</div>
                    <div class="dashboard-desc">Всего заказов</div>
                </div>
            </div>
        </div>

        <!-- Последние заявки -->
        <div class="dashboard-table-block">
            <h3 class="dashboard-table-title">Последние заявки</h3>
            <div class="dashboard-table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Материал</th>
                            <th>Количество</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\PurchaseRequest::with('material')->latest()->take(5)->get() as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->material->name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">
                                    @if($request->status == 'pending') Ожидание @elseif($request->status == 'approved') Утверждена @elseif($request->status == 'rejected') Отклонена @elseif($request->status == 'archived') Архив @elseif($request->status == 'completed') Завершено @else {{ $request->status }} @endif
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
            <div class="dashboard-chart-block" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(21,101,192,0.07); padding:2rem 1.5rem; margin-top:2.5rem; max-width:600px;">
                <h3 class="dashboard-table-title" style="margin-bottom:1.5rem;">График активности</h3>
                <canvas id="dashboardChart" height="260"></canvas>
            </div>
        @endif

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            // Локализация для графика
            const chartLabels = {
                requests: "Заявки",
                orders: "Заказы",
                month: "Месяц",
                count: "Количество"
            };
            fetch('/dashboard-stats')
                .then(res => res.json())
                .then(data => {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: chartLabels.requests,
                                    data: data.requests,
                                    backgroundColor: 'rgba(245, 158, 11, 0.85)',
                                    borderRadius: 12,
                                    maxBarThickness: 48,
                                    borderSkipped: false,
                                },
                                {
                                    label: chartLabels.orders,
                                    data: data.orders,
                                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                                    borderRadius: 12,
                                    maxBarThickness: 48,
                                    borderSkipped: false,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top', labels: { font: { size: 16 } } },
                                title: { display: false },
                                tooltip: {
                                    enabled: true,
                                    backgroundColor: '#fff',
                                    titleColor: '#1e293b',
                                    bodyColor: '#1e293b',
                                    borderColor: '#2563eb',
                                    borderWidth: 1,
                                    padding: 12,
                                    bodyFont: { size: 16 },
                                    titleFont: { size: 16 }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: true, color: '#e5e7eb' },
                                    title: { display: true, text: chartLabels.month, font: { size: 16 } },
                                    ticks: { font: { size: 15 } }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { display: true, color: '#e5e7eb' },
                                    title: { display: true, text: chartLabels.count, font: { size: 16 } },
                                    ticks: { font: { size: 15 }, stepSize: 1 }
                                }
                            },
                            animation: {
                                duration: 1200,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                });
        });
    </script>
</x-app-layout> 