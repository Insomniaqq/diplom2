<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Создать заказ
        </h2>
    </x-slot>

    <style>
        /* Basic form styling */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group:last-of-type {
            margin-bottom: 0.5rem;
        }

        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #4a5568; /* gray-700 */
            font-size: 0.875rem; /* sm */
        }

        .form-input,
        .form-textarea,
        .form-select {
            display: block;
            width: 100%;
            max-width: 400px; /* Added max-width */
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-input:focus,
        .form-textarea:focus,
         .form-select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-textarea {
            min-height: 80px;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #0056b3;
        }

         .btn-secondary {
             color: #fff;
             background-color: #6c757d;
             border-color: #6c757d;
         }

         .btn-secondary:hover {
             color: #fff;
             background-color: #545b62;
             border-color: #545b62;
         }

        .form-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 1rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 1rem;">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="supplier_id" class="form-label">Поставщик</label>
                            <select name="supplier_id" id="supplier_id" class="form-select" required>
                                <option value="">Выберите поставщика</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="expected_delivery_date" class="form-label">Ожидаемая дата поставки</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-input" value="{{ old('expected_delivery_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_terms" class="form-label">Условия оплаты</label>
                            <textarea name="payment_terms" id="payment_terms" class="form-textarea" required>{{ old('payment_terms') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="shipping_address" class="form-label">Адрес доставки</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-textarea" required>{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="notes" class="form-label">Примечания</label>
                            <textarea name="notes" id="notes" class="form-textarea">{{ old('notes') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="purchase_request_id" class="form-label">Заявка на закупку</label>
                            <select name="purchase_request_id" id="purchase_request_id" class="form-select" required>
                                <option value="">Выберите заявку</option>
                                @foreach($purchaseRequests as $request)
                                    <option value="{{ $request->id }}" @if(old('purchase_request_id') == $request->id) selected @endif>#{{ $request->id }} - {{ $request->material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit_price" class="form-label">Цена за единицу</label>
                            <input type="number" name="unit_price" id="unit_price" class="form-input" value="{{ old('unit_price') }}" required min="0" step="any">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary mr-2">Создать</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 