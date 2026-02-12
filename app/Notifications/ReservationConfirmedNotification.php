<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification
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
            ->subject('Votre réservation Aljannah Jet est confirmée')
            ->greeting('Bonjour ' . $this->reservation->first_name . ' ' . $this->reservation->last_name . ',')
            ->line('Bonne nouvelle ! Votre réservation a été traitée et validée par notre équipe.')
            ->line('Statut : CONFIRMÉE')
            ->line('Détails du vol :')
            ->line('Référence : REF-' . str_pad($this->reservation->id, 6, '0', STR_PAD_LEFT))
            ->line('Départ : ' . $this->reservation->departure_location . ' le ' . $this->reservation->departure_date)
            ->line('Arrivée : ' . $this->reservation->arrival_location . ' le ' . $this->reservation->arrival_date)
            ->line('Passagers : ' . $this->reservation->passengers)
            ->action('Télécharger mon ticket', route('reservations.pdf', $this->reservation->id))
            ->line('Nous vous souhaitons un agréable voyage avec Aljannah Jet.');
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