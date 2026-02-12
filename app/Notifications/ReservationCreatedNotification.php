<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCreatedNotification extends Notification
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
            ->subject('Confirmation de votre réservation - Aljannah Jet')
            ->greeting('Bonjour ' . $this->reservation->first_name . ' ' . $this->reservation->last_name . ',')
            ->line('Nous avons bien reçu votre demande de réservation et nous vous en remercions.')
            ->line('Voici les détails de votre vol :')
            ->line('Référence : REF-' . str_pad($this->reservation->id, 6, '0', STR_PAD_LEFT))
            ->line('Départ : ' . $this->reservation->departure_location . ' le ' . $this->reservation->departure_date) // Format date properly if needed, usually casted to Carbon
            ->line('Arrivée : ' . $this->reservation->arrival_location . ' le ' . $this->reservation->arrival_date)
            ->line('Passagers : ' . $this->reservation->passengers)
            ->action('Voir ma réservation', url('/check-status')) // Assuming a public status check page exists or will exist
            ->line('Notre équipe va traiter votre demande dans les plus brefs délais.')
            ->line('Merci de choisir Aljannah Jet pour vos déplacements.');
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