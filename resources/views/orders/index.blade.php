<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Заказы</h2>
            <div class="flex gap-2">
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Новый заказ
                </a>
            </div>
            <form action="{{ route('orders.index') }}" method="GET" class="mb-4">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Поиск по заказам..." value="{{ request('search') }}" class="form-input w-full">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-search"></i> Искать
                    </button>
                    @if(request('search'))
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            Сбросить
                        </a>
                    @endif
                </div>
            </form>
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
                    <th>Дата создания</th>
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
                    <td>{{ $order->created_at->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }}</td>
                    <td>{{ $order->expected_delivery_date->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }}</td>
                    <td>{{ $order->creator->name }}</td>
                    <td>
                        <div class="flex gap-2 items-center">
                            @if($order->status === 'pending')
                                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-primary btn-sm">Подтвердить</button>
                                </form>
                                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger btn-sm">Отменить</button>
                                </form>
                            @elseif($order->status === 'confirmed')
                                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="shipped">
                                    <button type="submit" class="btn btn-primary btn-sm">Отметить как отправленный</button>
                                </form>
                            @elseif($order->status === 'shipped')
                                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="btn btn-primary btn-sm">Отметить как полученный</button>
                                </form>
                            @endif
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