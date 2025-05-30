<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-center" style="color:#2563eb;">Помощь</h2>
    </x-slot>
    <div class="main-content">
        <div class="admin-panel-block" style="background: linear-gradient(135deg, #e3f0ff 0%, #f8fafc 100%); box-shadow: 0 4px 24px rgba(21,101,192,0.10); max-width: 950px; padding: 3.5rem 3rem 3rem 3rem;">
            <h3 class="admin-panel-title" style="color:#1565c0; text-align:center; margin-bottom:2.2rem; font-size:2.1rem;">
                <i class="fa-solid fa-circle-info" style="color:#2563eb; margin-right:0.7em; font-size:1.5em;"></i>
                Добро пожаловать в раздел помощи!
            </h3>
            <p class="admin-panel-desc" style="color:#2563eb; font-size:1.35em; text-align:center; margin-bottom:2.8rem;">
                Здесь вы найдёте описание доступных вам возможностей системы.
            </p>

            @if(auth()->user()->hasRole('Admin'))
                <ul style="max-width:900px; margin:2.5rem auto; font-size:1.35em; color:#1e293b; list-style:none; padding:0;">
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#2563eb; font-size:1.4em;"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span><b>Материалы:</b> <span style="color:#2563eb;">Полное управление справочником материалов.</span> Добавление, редактирование, удаление материалов.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#22c55e; font-size:1.4em;"><i class="fa-solid fa-truck-field"></i></span>
                        <span><b>Поставщики:</b> <span style="color:#22c55e;">Полное управление справочником поставщиков.</span> Добавление, редактирование, удаление поставщиков.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f59e0b; font-size:1.4em;"><i class="fa-solid fa-file-signature"></i></span>
                        <span><b>Заявки:</b> <span style="color:#f59e0b;">Полное управление заявками на закупку.</span> Создание, утверждение, отклонение заявок.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#a21caf; font-size:1.4em;"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                        <span><b>Заказы:</b> <span style="color:#a21caf;">Полное управление заказами.</span> Создание, редактирование, отслеживание статуса.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#0ea5e9; font-size:1.4em;"><i class="fa-solid fa-users"></i></span>
                        <span><b>Пользователи:</b> <span style="color:#0ea5e9;">Управление пользователями и ролями.</span> Добавление, редактирование, удаление пользователей.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#6366f1; font-size:1.4em;"><i class="fa-solid fa-gears"></i></span>
                        <span><b>Админ-панель:</b> <span style="color:#6366f1;">Полный доступ к управлению системой.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f43f5e; font-size:1.4em;"><i class="fa-solid fa-user-shield"></i></span>
                        <span><b>Права доступа:</b> <span style="color:#f43f5e;">Настройка прав пользователей и регистрация новых пользователей.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#64748b; font-size:1.4em;"><i class="fa-solid fa-box-archive"></i></span>
                        <span><b>Архив:</b> <span style="color:#64748b;">Управление архивом заказов.</span></span>
                    </li>
                </ul>
            @elseif(auth()->user()->hasRole('Manager'))
                <ul style="max-width:900px; margin:2.5rem auto; font-size:1.35em; color:#1e293b; list-style:none; padding:0;">
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#2563eb; font-size:1.4em;"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span><b>Материалы:</b> <span style="color:#2563eb;">Просмотр и управление справочником материалов.</span> Добавление и редактирование материалов.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#22c55e; font-size:1.4em;"><i class="fa-solid fa-truck-field"></i></span>
                        <span><b>Поставщики:</b> <span style="color:#22c55e;">Просмотр и управление справочником поставщиков.</span> Добавление и редактирование поставщиков.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f59e0b; font-size:1.4em;"><i class="fa-solid fa-file-signature"></i></span>
                        <span><b>Заявки:</b> <span style="color:#f59e0b;">Управление заявками на закупку.</span> Создание, утверждение, отклонение заявок.</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#a21caf; font-size:1.4em;"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                        <span><b>Заказы:</b> <span style="color:#a21caf;">Управление заказами.</span> Создание и отслеживание статуса заказов.</span>
                    </li>
                </ul>
            @elseif(auth()->user()->hasRole('Employee'))
                <ul style="max-width:900px; margin:2.5rem auto; font-size:1.35em; color:#1e293b; list-style:none; padding:0;">
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#2563eb; font-size:1.4em;"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span><b>Материалы:</b> <span style="color:#2563eb;">Просмотр склада.</span> Проверка наличия и характеристик материалов. Учет склада</span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f59e0b; font-size:1.4em;"><i class="fa-solid fa-file-signature"></i></span>
                        <span><b>Заявки:</b> <span style="color:#f59e0b;">Создание заявок на закупку.</span> Формирование заявок на необходимые материалы.</span>
                    </li>
                </ul>
            @else
                <ul style="max-width:900px; margin:2.5rem auto; font-size:1.35em; color:#1e293b; list-style:none; padding:0;">
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#2563eb; font-size:1.4em;"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span><b>Материалы:</b> <span style="color:#2563eb;">Просмотр справочника материалов.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#22c55e; font-size:1.4em;"><i class="fa-solid fa-truck-field"></i></span>
                        <span><b>Поставщики:</b> <span style="color:#22c55e;">Просмотр справочника поставщиков.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f59e0b; font-size:1.4em;"><i class="fa-solid fa-file-signature"></i></span>
                        <span><b>Заявки:</b> <span style="color:#f59e0b;">Просмотр и создание заявок на закупку.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#a21caf; font-size:1.4em;"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                        <span><b>Заказы:</b> <span style="color:#a21caf;">Просмотр заказов и их статусов.</span></span>
                    </li>
                    <li style="margin-bottom:1.7em; display:flex; align-items:flex-start; gap:1.2em;">
                        <span style="color:#f59e0b; font-size:1.4em;"><i class="fa-solid fa-bell"></i></span>
                        <span><b>Уведомления:</b> <span style="color:#f59e0b;">Просмотр уведомлений о важных событиях.</span></span>
                    </li>
                </ul>
            @endif

            <p style="color:#64748b; text-align:center; font-size:1.18em; margin-top:3.5rem;">
                <i class="fa-solid fa-circle-question" style="color:#2563eb; margin-right:0.7em; font-size:1.3em;"></i>
                Если у вас остались вопросы — обратитесь к администратору системы.
            </p>
        </div>
    </div>
</x-app-layout> 