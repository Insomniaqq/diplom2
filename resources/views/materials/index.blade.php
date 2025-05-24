<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
            {{ __('Материалы') }}
        </h2>
    </x-slot>

    <div style="margin-top: 1.2rem; margin-bottom: 1.2rem; display: flex; justify-content: flex-start; align-items: center; gap: 1rem;">
        <a href="{{ route('materials.create') }}" class="btn btn-primary">
            Добавить материал
        </a>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
            Управление разделами
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
                @php
                    $rowClass = '';
                    if ($material->current_quantity <= $material->min_quantity) {
                        $rowClass = 'bg-red-200'; // Ниже порога - красный фон
                    } elseif ($material->current_quantity <= $material->min_quantity * 1.2) {
                        $rowClass = 'bg-yellow-200'; // Желательно заказать - желтый фон
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $material->code }}</td>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->unit_of_measure }}</td>
                    <td>{{ $material->min_quantity }}</td>
                    <td>
                        @php
                            $bgColor = '';
                            if ($material->current_quantity >= 100) {
                                $bgColor = '#34D399'; // Более яркий зеленый для >= 100
                            } elseif ($material->current_quantity >= 30 && $material->current_quantity <= 60) {
                                $bgColor = '#F59E0B'; // Более яркий оранжевый для 30-60
                            } elseif ($material->current_quantity < 30) {
                                $bgColor = '#EF4444'; // Более яркий красный для < 30
                            }
                        @endphp
                        <span style="background-color: {{ $bgColor }}; padding: 0.25rem 0.5rem; border-radius: 9999px; display: inline-block; color: #ffffff;">
                            {{ $material->current_quantity }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary" style="margin-right: 0.5rem;">Редактировать</a>
                        <a href="{{ route('materials.show', $material) }}" class="btn btn-info" style="margin-right: 0.5rem;">Просмотр</a>
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