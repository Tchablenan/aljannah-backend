<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledNotification extends Notification
{
    use Queueable;

    public $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Annulation de votre réservation - Aljannah Jet')
            ->greeting('Bonjour ' . $this->reservation->first_name . ' ' . $this->reservation->last_name . ',')
            ->line('Votre réservation a été annulée.')
            ->line('Référence : REF-' . str_pad($this->reservation->id, 6, '0', STR_PAD_LEFT))
            ->line('Si vous avez des questions ou souhaitez effectuer une nouvelle réservation, n\'hésitez pas à nous contacter.')
            ->action('Nous contacter', url('/contact'))
            ->line('Merci de votre compréhension.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}