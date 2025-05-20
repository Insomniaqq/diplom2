<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Заявки на закупку</h2>
            <div class="flex gap-2">
                <a href="{{ route('purchase-requests.archived') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-box-archive"></i> Архив заявок
                </a>
                <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Новая заявка
                </a>
            </div>
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
                    <th>Материал</th>
                    <th>Количество</th>
                    <th>{{ __('messages.dashboard_table_status') }}</th>
                    <th>Заявитель</th>
                    <th>Дата создания</th>
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
                    <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('purchase-requests.edit', $request->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i> Редактировать
                            </a>
                            <a href="{{ route('purchase-requests.show', $request->id) }}" class="btn btn-sm btn-info">
                                <i class="fa-solid fa-eye"></i> Просмотр
                            </a>
                            <form action="{{ route('purchase-requests.destroy', $request->id) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить заявку?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i> Удалить
                                </button>
                            </form>
                            @if($request->status === 'approved' && !$request->order)
                                <a href="{{ route('orders.create', ['purchase_request_id' => $request->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-file-invoice-dollar"></i> Создать заказ
                                </a>
                            @endif
                            <form action="{{ route('purchase-requests.archive', $request) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-box-archive"></i> В архив
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $requests->links() }}
    </div>
</x-app-layout> 