<?php

namespace App\Mail;

use App\Models\Siswa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class RaportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $siswa;
    public $pdfPath;

    public function __construct(Siswa $siswa, $pdfPath = null)
    {
        $this->siswa = $siswa;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Raport Semester Ganjil - ' . $this->siswa->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.raport',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as('Raport_' . str_replace(' ', '_', $this->siswa->nama) . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
