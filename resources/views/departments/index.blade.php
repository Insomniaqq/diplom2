<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Список разделов
        </h2>
    </x-slot>

    <div class="container">
        <div class="flex justify-between items-center mb-4">
            <h1>Список разделов</h1>
            @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
                <a href="{{ route('departments.create') }}" class="btn btn-primary">Создать раздел</a>
            @endif
        </div>

        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Название</th>
                    <th class="px-4 py-2">Описание</th>
                    <th class="px-4 py-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td class="border px-4 py-2">{{ $department->name }}</td>
                    <td class="border px-4 py-2">{{ $department->description ?? '—' }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex gap-2">
                            <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-info">Просмотр</a>
                            @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">Редактировать</a>
                            @endif
                            @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Manager')
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот раздел?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout> 