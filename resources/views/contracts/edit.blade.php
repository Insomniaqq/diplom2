<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактировать контракт #{{ $contract->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Поставщик</label>
                        <select name="supplier_id" id="supplier_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Выберите поставщика</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $contract->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="number" class="block text-sm font-medium text-gray-700">Номер контракта</label>
                        <input type="text" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('number', $contract->number) }}" required>
                        @error('number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date_start" class="block text-sm font-medium text-gray-700">Дата начала</label>
                        <input type="date" name="date_start" id="date_start" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('date_start', $contract->date_start) }}" required>
                        @error('date_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date_end" class="block text-sm font-medium text-gray-700">Дата окончания</label>
                        <input type="date" name="date_end" id="date_end" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('date_end', $contract->date_end) }}">
                        @error('date_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Сумма</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('amount', $contract->amount) }}" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Активен</option>
                            <option value="closed" {{ old('status', $contract->status) == 'closed' ? 'selected' : '' }}>Закрыт</option>
                            <option value="cancelled" {{ old('status', $contract->status) == 'cancelled' ? 'selected' : '' }}>Аннулирован</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="file_path" class="block text-sm font-medium text-gray-700">Файл контракта (PDF)</label>
                        <input type="file" name="file_path" id="file_path" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="application/pdf">
                        @if($contract->file_path)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $contract->file_path) }}" target="_blank" class="text-blue-600 underline">Текущий файл</a>
                            </div>
                        @endif
                        @error('file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('contracts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 