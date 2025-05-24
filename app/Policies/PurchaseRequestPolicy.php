<?php

namespace App\Policies;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequestPolicy
{
    use HandlesAuthorization;

    public function approve(User $user, PurchaseRequest $purchaseRequest)
    {
        // Здесь можно добавить логику проверки прав на утверждение заявок
        // Например, только пользователи с определенной ролью могут утверждать заявки
        return $user->hasRole('Manager') || $user->hasRole('Admin');
    }
} 