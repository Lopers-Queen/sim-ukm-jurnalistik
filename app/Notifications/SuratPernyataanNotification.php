<?php

namespace App\Notifications;

use App\Models\SuratPernyataan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi Surat Pernyataan (FR-21)
 * Dikirim ke Ketum/Waketum saat ada surat pernyataan yang perlu disetujui.
 */
class SuratPernyataanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SuratPernyataan $suratPernyataan,
        public string $action = 'menunggu_konfirmasi',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $surat = $this->suratPernyataan->load(['anggota', 'event']);

        return (new MailMessage)
            ->subject('Surat Pernyataan - ' . ucfirst(str_replace('_', ' ', $this->action)))
            ->greeting('Halo ' . $notifiable->nama_lengkap . ',')
            ->line("Surat pernyataan dari {$surat->anggota->nama_lengkap} untuk event \"{$surat->event->nama_event}\" memerlukan persetujuan Anda.")
            ->action('Lihat Detail', url('/surat-pernyataan/' . $surat->id))
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'surat_pernyataan_id' => $this->suratPernyataan->id,
            'anggota_nama'        => $this->suratPernyataan->anggota->nama_lengkap ?? '',
            'event_nama'          => $this->suratPernyataan->event->nama_event ?? '',
            'action'              => $this->action,
            'message'             => 'Surat pernyataan baru menunggu persetujuan.',
        ];
    }
}
