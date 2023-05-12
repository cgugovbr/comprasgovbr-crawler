<?php

namespace Comprasnet\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ErroImportacao extends ComprasnetMailables
{
    use Queueable, SerializesModels;

    public $dados;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dados = null)
    {
        parent::__construct();

        $this->dados = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->dados) {
            return $this->to($this->emails_to)
                ->markdown('emails.erro.importacao')
                ->attach($this->dados['file_path']);
        } else {
            return $this->to($this->emails_to)
                ->markdown('emails.erro.importacao');
        }
    }
}
