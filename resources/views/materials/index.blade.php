<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
            {{ __('Материалы') }}
        </h2>
    </x-slot>

    <div style="margin-top: 1.2rem; margin-bottom: 1.2rem; display: flex; justify-content: flex-start; align-items: center;">
        <a href="{{ route('materials.create') }}" class="btn btn-primary">
            Добавить материал
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Наименование</th>
                    <th>Ед. изм.</th>
                    <th>Мин. кол-во</th>
                    <th>Текущее кол-во</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr>
                    <td>{{ $material->code }}</td>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->unit_of_measure }}</td>
                    <td>{{ $material->min_quantity }}</td>
                    <td>
                        <span class="status-badge {{ $material->current_quantity <= $material->min_quantity ? 'status-rejected' : 'status-approved' }}">
                            {{ $material->current_quantity }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary" style="margin-right: 0.5rem;">Редактировать</a>
                        <form action="{{ route('materials.destroy', $material) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $materials->links() }}
    </div>
</x-app-layout> 