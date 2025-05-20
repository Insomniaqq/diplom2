<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Создать заказ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Поставщик</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                <option value="">Выберите поставщика</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label">Ожидаемая дата поставки</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_terms" class="form-label">Условия оплаты</label>
                            <textarea name="payment_terms" id="payment_terms" class="form-control" required>{{ old('payment_terms') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Адрес доставки</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-control" required>{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Примечания</label>
                            <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="purchase_request_id" class="form-label">Заявка на закупку</label>
                            <select name="purchase_request_id" id="purchase_request_id" class="form-control" required>
                                <option value="">Выберите заявку</option>
                                @foreach($purchaseRequests as $request)
                                    <option value="{{ $request->id }}" @if(old('purchase_request_id') == $request->id) selected @endif>#{{ $request->id }} - {{ $request->material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="unit_price" class="form-label">Цена за единицу</label>
                            <input type="number" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price') }}" required min="0" step="any">
                        </div>
                        <button type="submit" class="btn btn-success">Создать</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Отмена</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 