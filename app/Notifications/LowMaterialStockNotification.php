<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material;

class LowMaterialStockNotification extends Notification
{
    use Queueable;

    protected $material;
    protected $currentQuantity;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Material $material, $currentQuantity)
    {
        $this->material = $material;
        $this->currentQuantity = $currentQuantity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Or specify other channels like 'mail' if needed
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = '';
        $url = ''; // Define a relevant URL if applicable, e.g., route to material details

        if ($this->currentQuantity > 0) {
            $message = "Низкий запас материала: " . $this->material->name . ". Текущее количество: " . $this->currentQuantity . " " . $this->material->unit_of_measure . ".";
            // Example URL: $url = route('materials.show', $this->material->id);
        } else {
            $message = "Материал закончился: " . $this->material->name . ". Текущее количество: 0 " . $this->material->unit_of_measure . ".";
            // Example URL: $url = route('materials.show', $this->material->id);
        }

        return [
            'title' => $this->currentQuantity > 0 ? 'Низкий запас материала' : 'Материал закончился',
            'message' => $message,
            'material_id' => $this->material->id,
            'material_name' => $this->material->name,
            'current_quantity' => $this->currentQuantity,
            'unit_of_measure' => $this->material->unit_of_measure,
            'url' => $url,
        ];
    }
} 