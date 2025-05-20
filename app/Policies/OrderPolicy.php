<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function updateStatus(User $user, Order $order)
    {
        // Разрешаем обновление статуса только менеджерам и администраторам
        return $user->hasRole(['manager', 'admin']);
    }

    public function create(User $user)
    {
        // Разрешаем создание заказов только менеджерам и администраторам
        return $user->hasRole(['manager', 'admin']);
    }

    public function view(User $user, Order $order)
    {
        // Разрешаем просмотр заказов всем авторизованным пользователям
        return true;
    }
} 