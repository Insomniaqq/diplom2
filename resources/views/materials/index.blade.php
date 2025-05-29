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

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('materials.index') }}" class="mb-4 flex items-center gap-4">
        <div>
            <label for="name" class="form-label sr-only">Название</label>
            <input type="text" name="name" id="name" placeholder="Фильтр по названию" value="{{ request('name') }}" class="form-input w-32">
        </div>
        <div>
            <label for="code" class="form-label sr-only">Код</label>
            <input type="text" name="code" id="code" placeholder="Фильтр по коду" value="{{ request('code') }}" class="form-input w-32">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Фильтровать</button>
        </div>
        @if(request('name') || request('code'))
            <div>
                <a href="{{ route('materials.index') }}" class="btn btn-secondary">Сбросить</a>
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
                        @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager'))
                            <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary" style="margin-right: 0.5rem;">Редактировать</a>
                            <a href="{{ route('materials.show', $material) }}" class="btn btn-info" style="margin-right: 0.5rem;">Просмотр</a>
                            <form action="{{ route('materials.destroy', $material) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        @else
                             <a href="{{ route('materials.show', $material) }}" class="btn btn-info" style="margin-right: 0.5rem;">Просмотр</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        @if ($materials->hasPages())
            <nav>
                <ul class="pagination" style="display: flex; gap: 0.3rem; list-style: none; padding: 0; align-items: center;">
                    {{-- Previous Page Link --}}
                    @if ($materials->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="Назад">
                            <span class="page-link" aria-hidden="true" style="background: none; color: #a1a1aa; border: none; font-size: 1.5em; padding: 0 0.7em;">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $materials->previousPageUrl() }}" rel="prev" aria-label="Назад" style="background: none; color: #2563eb; border: none; font-size: 1.5em; padding: 0 0.7em; text-decoration: none;">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($materials->getUrlRange(1, $materials->lastPage()) as $page => $url)
                        @if ($page == $materials->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="background: none; color: #2563eb; font-weight: bold; border-bottom: 2px solid #2563eb; border-radius: 0; padding: 0 0.7em;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="background: none; color: #2563eb; border: none; padding: 0 0.7em; text-decoration: none;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($materials->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $materials->nextPageUrl() }}" rel="next" aria-label="Вперёд" style="background: none; color: #2563eb; border: none; font-size: 1.5em; padding: 0 0.7em; text-decoration: none;">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Вперёд">
                            <span class="page-link" aria-hidden="true" style="background: none; color: #a1a1aa; border: none; font-size: 1.5em; padding: 0 0.7em;">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</x-app-layout> 