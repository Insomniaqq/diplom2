<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Бюджет: {{ $budget->year }}{{ $budget->month ? ' / ' . $budget->month : '' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-2"><b>Год:</b> {{ $budget->year }}</div>
                <div class="mb-2"><b>Месяц:</b> {{ $budget->month ? $budget->month : '-' }}</div>
                <div class="mb-2"><b>Плановый бюджет:</b> {{ number_format($budget->amount, 2) }} ₽</div>
                <div class="mb-2"><b>Потрачено:</b> {{ number_format($budget->spent, 2) }} ₽</div>
                <div class="mb-2"><b>Остаток:</b> {{ number_format($budget->amount - $budget->spent, 2) }} ₽</div>
                <div class="mb-2"><b>Комментарий:</b> {{ $budget->comment }}</div>
                <div class="flex gap-2 mt-6">
                    <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-warning">Редактировать</a>
                    <a href="{{ route('budgets.index') }}" class="btn btn-secondary">К списку</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 