<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Новый бюджет
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('budgets.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="year" class="block text-sm font-medium text-gray-700">Год</label>
                        <input type="number" name="year" id="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('year', date('Y')) }}" required>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="month" class="block text-sm font-medium text-gray-700">Месяц (опционально)</label>
                        <input type="number" name="month" id="month" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('month') }}" min="1" max="12">
                        @error('month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Плановый бюджет (₽)</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('amount') }}" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700">Комментарий</label>
                        <input type="text" name="comment" id="comment" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('comment') }}">
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('budgets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Отмена</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 