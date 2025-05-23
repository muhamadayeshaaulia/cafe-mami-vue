<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSaleInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @param  $sale
     * @param  $pdf
     * @return void
     */
    public function __construct($sale, $pdf)
    {
        $this->sale = $sale;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nota Penjualan - ' . $this->sale->invoice)
                    ->view('emails.sale_invoice')
                    ->attachData($this->pdf->output(), 'invoice-'.$this->sale->invoice.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}


        