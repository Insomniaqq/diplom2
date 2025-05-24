<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Админ-панель</h2>
    </x-slot>
    <div class="main-content">
        <div class="admin-panel-block">
            <h3 class="admin-panel-title">Добро пожаловать в административную панель!</h3>
            <p class="admin-panel-desc">Здесь вы можете управлять пользователями, материалами, поставщиками, заявками и заказами.</p>
            <div class="admin-panel-links">
                <a href="{{ route('materials.index') }}" class="btn btn-primary"><i class="fa-solid fa-boxes-stacked"></i> Материалы</a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-primary"><i class="fa-solid fa-truck-field"></i> Поставщики</a>
                <a href="{{ route('purchase-requests.index') }}" class="btn btn-primary"><i class="fa-solid fa-file-signature"></i> Заявки</a>
                <a href="{{ route('orders.index') }}" class="btn btn-primary"><i class="fa-solid fa-file-invoice-dollar"></i> Заказы</a>
                <a href="{{ route('roles.index') }}" class="btn btn-primary"><i class="fa-solid fa-user-shield"></i> Роли</a>
                <a href="{{ route('users.index') }}" class="btn btn-primary"><i class="fa-solid fa-users"></i> Пользователи</a>
                <a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa-solid fa-user-plus"></i> Добавить пользователя</a>
                <a href="{{ route('settings.index') }}" class="btn btn-secondary"><i class="fa-solid fa-gear"></i> Настройки</a>
                <div class="dropdown" style="display:inline-block;position:relative;">
                    <a href="#" class="btn btn-primary dropdown-toggle"><i class="fa-solid fa-chart-line"></i> Аналитика <i class="fa fa-caret-down"></i></a>
                    <div class="dropdown-menu" style="display:none;position:absolute;left:0;top:100%;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.1);z-index:1000;min-width:180px;">
                        <a href="{{ route('reports.budget') }}" class="dropdown-item" style="display:block;padding:8px 16px;">Бюджет</a>
                        <a href="{{ route('reports.requests') }}" class="dropdown-item" style="display:block;padding:8px 16px;">Заявки</a>
                        <a href="{{ route('reports.suppliers') }}" class="dropdown-item" style="display:block;padding:8px 16px;">Поставщики</a>
                        <a href="{{ route('reports.monthly-norms') }}" class="dropdown-item" style="display:block;padding:8px 16px;">Месячные нормы</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Логика для выпадающего меню аналитики в админке
    document.querySelectorAll('.admin-panel-links .dropdown-toggle').forEach(function(el) {
        el.addEventListener('mouseover', function() {
            this.nextElementSibling.style.display = 'block';
        });
        el.parentElement.addEventListener('mouseleave', function() {
            el.querySelector('.dropdown-menu').style.display = 'none';
        });
    });
</script> 