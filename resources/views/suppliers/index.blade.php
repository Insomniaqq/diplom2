<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
            {{ __('Поставщики') }}
        </h2>
    </x-slot>

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
        <div style="margin-top: 1.2rem; margin-bottom: 1.2rem; display: flex; justify-content: flex-start; align-items: center;">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                Добавить поставщика
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ИНН</th>
                    <th>Наименование</th>
                    <th>Контактное лицо</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->inn }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary" style="margin-right: 0.5rem;">Редактировать</a>
                        @endif
                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info" style="margin-right: 0.5rem;">Просмотр</a>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $suppliers->links() }}
    </div>
</x-app-layout> 