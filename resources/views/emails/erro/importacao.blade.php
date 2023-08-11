@component('mail::message')
# Importação de dados - Comprasnet Crawler

Houve algum erro na importação dos dados...

_Environment: {{ App::environment() }}_

@if(isset($dados))
    O erro está relacionado à:
    {{ is_array($dados) ? implode(',', $dados) : $dados }}
@endif

@include('emails/common/assinatura')
@endcomponent
