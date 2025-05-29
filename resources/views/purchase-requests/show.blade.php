<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Заявка #{{ $request->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Детали заявки</h3>
                <div class="mb-2"><b>Материал:</b> {{ $request->material->name }}</div>
                <div class="mb-2"><b>Количество:</b> {{ $request->quantity }}</div>
                <div class="mb-2"><b>Обоснование:</b> {{ $request->justification }}</div>
                <div class="mb-2"><b>Статус:</b> <span class="status-badge status-{{ $request->status }}">{{ __('messages.status_' . $request->status) }}</span></div>
                <div class="mb-2"><b>Заявитель:</b> {{ $request->requester->name }}</div>
                <div class="mb-2"><b>Дата создания:</b> {{ $request->created_at->format('d.m.Y H:i') }}</div>

                @if(Auth::user()->role === 'Manager' || Auth::user()->role === 'Admin')
                    <div class="flex gap-2 mt-4">
                        @if($request->status === 'pending')
                            <form action="{{ route('purchase-requests.approve', $request) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Одобрить</button>
                            </form>
                            <form action="{{ route('purchase-requests.reject', $request) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите отклонить заявку?');">
                                @csrf
                                <input type="hidden" name="rejection_reason" value="Отклонено администратором">
                                <button type="submit" class="btn btn-danger">Отклонить</button>
                            </form>
                        @endif
                        @if($request->status === 'approved')
                            <form action="{{ route('purchase-requests.complete', $request) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Завершить</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">История изменений статусов</h3>
                @if($statusLogs->isEmpty())
                    <div class="text-gray-500">Нет истории изменений.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Дата</th>
                                <th class="px-4 py-2">Пользователь</th>
                                <th class="px-4 py-2">Было</th>
                                <th class="px-4 py-2">Стало</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusLogs as $log)
                                <tr>
                                    <td class="px-4 py-2">{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-4 py-2">{{ $log->user ? $log->user->name : 'Система' }}</td>
                                    <td class="px-4 py-2">{{ $log->old_status ? __('messages.status_' . $log->old_status) : '-' }}</td>
                                    <td class="px-4 py-2">{{ __('messages.status_' . $log->new_status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 