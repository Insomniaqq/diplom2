<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Настройки
        </h2>
    </x-slot>

    <div class="container">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">Настройки</h1>

                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="monthly_norm_notification_threshold" class="block text-sm font-medium text-gray-700">Порог уведомления о месячной норме (%)</label>
                        <input type="number" name="monthly_norm_notification_threshold" id="monthly_norm_notification_threshold" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('monthly_norm_notification_threshold', $threshold) }}" min="0" max="100" required>
                        @error('monthly_norm_notification_threshold')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 