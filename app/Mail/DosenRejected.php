<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DosenRejected extends Mailable
{
    use Queueable, SerializesModels;

    public string $dosenName;
    public string $dosenEmail;
    public string $rejectedBy;
    public string $rejectedAt;
    public string $adminEmail;

    public function __construct(string $dosenName, string $dosenEmail, string $rejectedBy)
    {
        $this->dosenName  = $dosenName;
        $this->dosenEmail = $dosenEmail;
        $this->rejectedBy = $rejectedBy;
        $this->rejectedAt = now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';
        $this->adminEmail = config('mail.from.address');
    }

    public function build(): self
    {
        return $this
            ->subject('❌ Pendaftaran Akun Rento Tidak Disetujui')
            ->view('emails.dosen_rejected')
            ->with([
                'dosenName'  => $this->dosenName,
                'dosenEmail' => $this->dosenEmail,
                'rejectedBy' => $this->rejectedBy,
                'rejectedAt' => $this->rejectedAt,
                'adminEmail' => $this->adminEmail,
            ]);
    }
}