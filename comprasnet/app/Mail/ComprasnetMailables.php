<?php

namespace Comprasnet\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;

class ComprasnetMailables extends Mailable
{
    use Queueable, SerializesModels;

    protected $emails_to;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (strtolower(App::environment()) == 'production') {
            $this->emails_to = config('comprasnet.emails_to');
        } else {
            $this->emails_to = config('comprasnet.emails_to_support');
        }
    }
}
