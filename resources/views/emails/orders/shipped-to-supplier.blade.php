<div>
    <h1>Детали заказа №{{ $order->order_number }}</h1>

    <p>Уважаемый поставщик {{ $order->supplier->name }},</p>

    <p>Пожалуйста, подготовьте следующий заказ:</p>

    <ul>
        <li>Материал: {{ $order->purchaseRequest->material->name }}</li>
        <li>Количество: {{ $order->purchaseRequest->quantity }} {{ $order->purchaseRequest->material->unit_of_measure }}</li>
        <li>Цена за единицу: {{ $order->unit_price }}</li>
        <li>Общая сумма: {{ $order->total_amount }}</li>
    </ul>

    <p>Ожидаемая дата доставки: {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('d.m.Y') }}</p>

    <p>Условия оплаты: {{ $order->payment_terms }}</p>

    <p>Адрес доставки: {{ $order->shipping_address }}</p>

    @if($order->notes)
        <p>Примечания: {{ $order->notes }}</p>
    @endif

    <p>С уважением,</p>
    <p>Система управления закупками</p>
</div> 