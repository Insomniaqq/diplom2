<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Архив заявок на закупку</h2>
            <a href="{{ route('purchase-requests.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i> К активным заявкам
            </a>
        </div>
    </x-slot>

    <div class="main-content">
        <div class="card">
            @if($requests->count() === 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fa-solid fa-box-archive text-4xl mb-4"></i>
                    <p>Архив заявок пуст</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Материал</th>
                                <th>Количество</th>
                                <th>{{ __('messages.dashboard_table_status') }}</th>
                                <th>Заявитель</th>
                                <th>Дата архивации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->material->name }}</td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $request->status }}">
                                            {{ __('messages.status_' . $request->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->requester->name }}</td>
                                    <td>{{ $request->archived_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('purchase-requests.unarchive', $request) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">
                                                <i class="fa-solid fa-box-open"></i> Разархивировать
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 