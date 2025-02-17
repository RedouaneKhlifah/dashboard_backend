<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Devis;
use Illuminate\Support\Facades\Log;

class DevisPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;
    protected $pdfPath;

    public function __construct($pdfPath, Devis $devis)
    {
        $this->pdfPath = $pdfPath;
        $this->devis = $devis;
    }

    public function build()
    {
        Log::info('Attaching PDF to email', [
            'pdfPath' => $this->pdfPath,
            'devisReference' => $this->devis->reference,
        ]);

        return $this->subject('Devis Details - ' . $this->devis->reference)
                    ->view('emails.devis')
                    ->attach($this->pdfPath, [
                        'as' => 'devis-' . $this->devis->reference . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}