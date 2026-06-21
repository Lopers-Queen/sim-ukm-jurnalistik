<?php

namespace App\Jobs;

use App\Models\Anggota;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Job: Kirim email kredensial ke anggota baru (FR-02)
 */
class SendCredentialEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Anggota $anggota,
        public string $defaultPassword,
    ) {}

    public function handle(): void
    {
        Mail::raw(
            "Halo {$this->anggota->nama_lengkap},\n\n" .
            "Akun SIM UKM Jurnalistik Anda telah dibuat.\n\n" .
            "Username (NIM): {$this->anggota->nim}\n" .
            "Password: {$this->defaultPassword}\n\n" .
            "Anda WAJIB mengganti password saat login pertama kali.\n\n" .
            "Salam,\nAdmin SIM UKM Jurnalistik\nPoliteknik Negeri Samarinda",
            function ($message) {
                $message->to($this->anggota->email)
                    ->subject('Kredensial Akun SIM UKM Jurnalistik');
            }
        );
    }
}
