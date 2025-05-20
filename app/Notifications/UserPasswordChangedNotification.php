<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserPasswordChangedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Пароль изменён',
            'message' => 'Ваш пароль был изменён администратором. Если вы этого не делали — обратитесь к администратору.',
            'link' => null,
        ];
    }
} 