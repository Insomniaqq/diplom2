<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Поставщик: {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Информация о поставщике</h3>
                <div class="mb-2"><b>ИНН:</b> {{ $supplier->inn }}</div>
                <div class="mb-2"><b>Контактное лицо:</b> {{ $supplier->contact_person }}</div>
                <div class="mb-2"><b>Телефон:</b> {{ $supplier->phone }}</div>
                <div class="mb-2"><b>Email:</b> {{ $supplier->email }}</div>
                <div class="mb-2"><b>Адрес:</b> {{ $supplier->address }}</div>
                <div class="mb-2"><b>Доп. информация:</b> {{ $supplier->additional_info ?? '-' }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Контракты</h3>
                @if($supplier->contracts->isEmpty())
                    <div class="text-gray-500">Нет контрактов.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Номер</th>
                                <th class="px-4 py-2">Дата начала</th>
                                <th class="px-4 py-2">Дата окончания</th>
                                <th class="px-4 py-2">Сумма</th>
                                <th class="px-4 py-2">Статус</th>
                                <th class="px-4 py-2">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->contracts as $contract)
                                <tr>
                                    <td class="px-4 py-2">{{ $contract->number }}</td>
                                    <td class="px-4 py-2">{{ $contract->date_start }}</td>
                                    <td class="px-4 py-2">{{ $contract->date_end ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ number_format($contract->amount, 2) }} ₽</td>
                                    <td class="px-4 py-2">{{ $contract->status }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Заказы</h3>
                @if($supplier->orders->isEmpty())
                    <div class="text-gray-500">Нет заказов.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Номер заказа</th>
                                <th class="px-4 py-2">Сумма</th>
                                <th class="px-4 py-2">Статус</th>
                                <th class="px-4 py-2">Ожидаемая дата</th>
                                <th class="px-4 py-2">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->orders as $order)
                                <tr>
                                    <td class="px-4 py-2">{{ $order->order_number }}</td>
                                    <td class="px-4 py-2">{{ number_format($order->total_amount, 2) }} ₽</td>
                                    <td class="px-4 py-2">{{ $order->status }}</td>
                                    <td class="px-4 py-2">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('d.m.Y') : '-' }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 