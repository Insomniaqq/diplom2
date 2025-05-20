<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Архив заказов</h2>
            <a href="{{ route('orders.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i> К активным заказам
            </a>
        </div>
    </x-slot>

    <div class="main-content">
        <div class="card">
            <p>Всего архивированных заказов: {{ $orders->total() }}</p>
            @if($orders->count() === 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fa-solid fa-box-archive text-4xl mb-4"></i>
                    <p>Архив заказов пуст</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Номер заказа</th>
                                <th>Поставщик</th>
                                <th>Сумма</th>
                                <th>{{ __('messages.dashboard_table_status') }}</th>
                                <th>Дата доставки</th>
                                <th>Дата архивации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number ?? '—' }}</td>
                                    <td>{{ $order->supplier->name ?? '—' }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} ₽</td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ __('messages.status_' . $order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($order->expected_delivery_date)->format('d.m.Y') ?? '—' }}</td>
                                    <td>{{ optional($order->archived_at)->format('d.m.Y H:i') ?? '—' }}</td>
                                    <td>
                                        <form action="{{ route('orders.unarchive', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">
                                                <i class="fa-solid fa-box-open"></i> Разархивировать
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 