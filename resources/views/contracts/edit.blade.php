<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактировать контракт #{{ $contract->id }}
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
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="supplier_id" class="form-label">Поставщик</label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">Выберите поставщика</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $contract->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number" class="form-label">Номер контракта</label>
                        <input type="text" name="number" id="number" class="form-input" value="{{ old('number', $contract->number) }}" required>
                        @error('number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_start" class="form-label">Дата начала</label>
                        <input type="date" name="date_start" id="date_start" class="form-input" value="{{ old('date_start', $contract->date_start) }}" required>
                        @error('date_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_end" class="form-label">Дата окончания</label>
                        <input type="date" name="date_end" id="date_end" class="form-input" value="{{ old('date_end', $contract->date_end) }}">
                        @error('date_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount" class="form-label">Сумма</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-input" value="{{ old('amount', $contract->amount) }}" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Статус</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Активен</option>
                            <option value="closed" {{ old('status', $contract->status) == 'closed' ? 'selected' : '' }}>Закрыт</option>
                            <option value="cancelled" {{ old('status', $contract->status) == 'cancelled' ? 'selected' : '' }}>Аннулирован</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file_path" class="form-label">Файл контракта (PDF)</label>
                        <input type="file" name="file_path" id="file_path" class="form-input" accept="application/pdf">
                        @if($contract->file_path)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $contract->file_path) }}" target="_blank" class="text-blue-600 underline">Текущий файл</a>
                            </div>
                        @endif
                        @error('file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary mr-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 