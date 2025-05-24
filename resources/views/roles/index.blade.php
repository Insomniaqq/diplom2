<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Роли
        </h2>
    </x-slot>

<div class="container">
    
    <div style="clear:both;"></div>
    @if($roles->count())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Пользователей</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $roleTranslations = [
                        'Admin' => 'Администратор',
                        'Manager' => 'Зав.склада',
                        'Employee' => 'Работник склада',
                    ];
                @endphp
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $roleTranslations[$role->name] ?? $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $userCounts[$role->id] ?? 0 }}</td>
                        <td>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить роль?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">Ролей пока нет.</div>
    @endif
</div>
</x-app-layout> 