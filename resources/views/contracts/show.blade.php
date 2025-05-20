<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Контракт #{{ $contract->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Детали контракта</h3>
                <div class="mb-2"><b>Поставщик:</b> {{ $contract->supplier->name }}</div>
                <div class="mb-2"><b>Номер:</b> {{ $contract->number }}</div>
                <div class="mb-2"><b>Дата начала:</b> {{ $contract->date_start }}</div>
                <div class="mb-2"><b>Дата окончания:</b> {{ $contract->date_end ?? '-' }}</div>
                <div class="mb-2"><b>Сумма:</b> {{ number_format($contract->amount, 2) }} ₽</div>
                <div class="mb-2"><b>Статус:</b> {{ $contract->status }}</div>
                <div class="mb-2"><b>Файл:</b>
                    @if($contract->file_path)
                        <a href="{{ asset('storage/' . $contract->file_path) }}" target="_blank" class="text-blue-600 underline">Скачать</a>
                    @else
                        -
                    @endif
                </div>
                <div class="flex gap-2 mt-6">
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                        <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-warning">Редактировать</a>
                    @endif
                    <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> К списку
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 