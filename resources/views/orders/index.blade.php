<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Заказы</h2>
            <div class="flex gap-2">
                <a href="{{ route('orders.archived') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-box-archive"></i> Архив заказов
                </a>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Новый заказ
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Поставщик</th>
                    <th>Сумма</th>
                    <th>{{ __('messages.dashboard_table_status') }}</th>
                    <th>Ожидаемая дата</th>
                    <th>Создал</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->supplier->name }}</td>
                    <td>{{ number_format($order->total_amount, 2) }} ₽</td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ __('messages.status_' . $order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->expected_delivery_date->format('d.m.Y') }}</td>
                    <td>{{ $order->creator->name }}</td>
                    <td>
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.update-status', $order) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-primary">Подтвердить</button>
                            </form>
                            <form action="{{ route('orders.update-status', $order) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-danger">Отменить</button>
                            </form>
                        @elseif($order->status === 'confirmed')
                            <form action="{{ route('orders.update-status', $order) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="shipped">
                                <button type="submit" class="btn btn-primary">Отметить как отправленный</button>
                            </form>
                        @elseif($order->status === 'shipped')
                            <form action="{{ route('orders.update-status', $order) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="btn btn-primary">Отметить как полученный</button>
                            </form>
                        @endif
                        <div class="flex gap-2">
                            <form action="{{ route('orders.archive', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-box-archive"></i> В архив
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $orders->links() }}
    </div>
</x-app-layout> 