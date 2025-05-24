<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\Department;

class MonthlyNormExceededNotification extends Notification
{
    use Queueable;

    public $material;
    public $department;
    public $percentage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Material $material, Department $department, $percentage)
    {
        $this->material = $material;
        $this->department = $department;
        $this->percentage = $percentage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Уведомление будет сохраняться в базе данных
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'material_id' => $this->material->id,
            'material_name' => $this->material->name,
            'department_id' => $this->department->id,
            'department_name' => $this->department->name,
            'percentage' => $this->percentage,
            'message' => "Отдел \"{$this->department->name}\" использовал {$this->percentage}% месячной нормы материала \"{$this->material->name}\"."
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
}
