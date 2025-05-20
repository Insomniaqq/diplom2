<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Бюджеты</h2>
            <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Новый бюджет
            </a>
        </div>
    </x-slot>

    <div class="table-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Год</th>
                    <th>Месяц</th>
                    <th>План</th>
                    <th>Потрачено</th>
                    <th>Остаток</th>
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $budget)
                <tr>
                    <td>{{ $budget->year }}</td>
                    <td>{{ $budget->month ? $budget->month : '-' }}</td>
                    <td>{{ number_format($budget->amount, 2) }} ₽</td>
                    <td>{{ number_format($budget->spent, 2) }} ₽</td>
                    <td>{{ number_format($budget->amount - $budget->spent, 2) }} ₽</td>
                    <td>{{ $budget->comment }}</td>
                    <td>
                        <a href="{{ route('budgets.show', $budget->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST" class="inline" onsubmit="return confirm('Удалить бюджет?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout> 