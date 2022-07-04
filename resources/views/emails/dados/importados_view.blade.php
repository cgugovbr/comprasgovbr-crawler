# Importação de dados - Comprasnet Crawler

Os dados foram importados com sucesso!

_Environment: {{ App::environment() }}_

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

@if(isset($dados))
  <h3>Arquivo no servidor: {{ $dados['file_path'] }}</h3>
@endif

@include('emails/common/assinatura')
