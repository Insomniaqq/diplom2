<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
            {{ __('Поставщики') }}
        </h2>
    </x-slot>

    @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
        <div style="margin-top: 1.2rem; margin-bottom: 1.2rem; display: flex; justify-content: flex-start; align-items: center;">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                Добавить поставщика
            </a>
        </div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('suppliers.index') }}" class="mb-4 flex items-center gap-4">
        <div>
            <label for="inn" class="form-label sr-only">ИНН</label>
            <input type="text" name="inn" id="inn" placeholder="Фильтр по ИНН" value="{{ request('inn') }}" class="form-input w-32">
        </div>
        <div>
            <label for="name" class="form-label sr-only">Наименование</label>
            <input type="text" name="name" id="name" placeholder="Фильтр по названию" value="{{ request('name') }}" class="form-input w-32">
        </div>
        <div>
            <label for="contact_person" class="form-label sr-only">Контактное лицо</label>
            <input type="text" name="contact_person" id="contact_person" placeholder="Фильтр по контактному лицу" value="{{ request('contact_person') }}" class="form-input w-32">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Фильтровать</button>
        </div>
        @if(request('inn') || request('name') || request('contact_person'))
            <div>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Сбросить</a>
            </div>
        @endif
    </form>

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
                        @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary" style="margin-right: 0.5rem;">Редактировать</a>
                        @endif
                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info" style="margin-right: 0.5rem;">Просмотр</a>
                        @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
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