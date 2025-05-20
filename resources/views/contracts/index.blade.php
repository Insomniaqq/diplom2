<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Контракты</h2>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Новый контракт
                </a>
            @endif
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
                    <th>ID</th>
                    <th>Поставщик</th>
                    <th>Номер</th>
                    <th>Дата начала</th>
                    <th>Дата окончания</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                <tr>
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->supplier->name }}</td>
                    <td>{{ $contract->number }}</td>
                    <td>{{ $contract->date_start }}</td>
                    <td>{{ $contract->date_end ?? '-' }}</td>
                    <td>{{ number_format($contract->amount, 2) }} ₽</td>
                    <td>{{ $contract->status }}</td>
                    <td>
                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-info">
                            <i class="fa-solid fa-eye"></i> Просмотр
                        </a>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i> Редактировать
                            </a>
                            <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="inline" onsubmit="return confirm('Удалить контракт?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i> Удалить
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout> 