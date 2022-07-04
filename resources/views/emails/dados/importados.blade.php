@component('mail::message')
# Importação de dados - Comprasnet Crawler

Os dados foram importados com sucesso!

_Environment: {{ App::environment() }}_

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

@if(isset($dados))
  <small>O arquivos em anexo está também no servidor em: {{ $dados['file_path'] }}</small>
@endif

@include('emails/common/assinatura')
@endcomponent
