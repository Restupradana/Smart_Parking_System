<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiSampahPenuh extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $lokasi;

    /**
     * Create a new notification instance.
     *
     * @param  string  $lokasi
     */
    public function __construct(string $lokasi)
    {
        $this->lokasi = $lokasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail']; // Bisa juga tambahkan 'database', 'nexmo', 'whatsapp' jika diperlukan
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sampah Penuh - Lokasi: ' . $this->lokasi)
            ->line('Ada laporan sampah penuh.')
            ->line('Silakan lakukan penjemputan di lokasi berikut:')
            ->line('Lokasi: ' . $this->lokasi)
            ->line('Terima kasih telah menjaga kebersihan lingkungan!');
    }

    /**
     * Get the array representation of the notification (opsional untuk database).
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'lokasi' => $this->lokasi,
            'pesan' => 'Sampah penuh, segera lakukan penjemputan.',
        ];
    }
}
