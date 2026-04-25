<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DosenApproved extends Mailable
{
    use Queueable, SerializesModels;

    public string $dosenName;
    public string $dosenEmail;
    public string $approvedBy;
    public string $approvedAt;
    public string $loginUrl;

    public function __construct(string $dosenName, string $dosenEmail, string $approvedBy)
    {
        $this->dosenName  = $dosenName;
        $this->dosenEmail = $dosenEmail;
        $this->approvedBy = $approvedBy;
        $this->approvedAt = now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';
        $this->loginUrl   = config('app.url') . '/login';
    }

    public function build(): self
    {
        return $this
            ->subject('✅ Akun Rento Anda Telah Disetujui!')
            ->view('emails.dosen_approved')
            ->with([
                'dosenName'  => $this->dosenName,
                'dosenEmail' => $this->dosenEmail,
                'approvedBy' => $this->approvedBy,
                'approvedAt' => $this->approvedAt,
                'loginUrl'   => $this->loginUrl,
            ]);
    }
}