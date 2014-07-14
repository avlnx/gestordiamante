@extends('layouts.default')


@section('content')

<h1>Estatísticas</h1>   

<p class="lead">Escolha uma das visualizações abaixo</p>

<div class="btn-group" data-toggle="buttons-radio">
<ul class="inline">
   <li><button href="#" id='vendas-stats' class='btn btn-small pretty-button' data-toggle="button">Vendas</button></li>
   <li><button href="#" id='stock-stats' class='btn btn-small pretty-button' data-toggle="button">Estoque</button></li>
</ul>
</div>

<div id='vendas-stats-wrapper' class='well' style='display: none'>
<h3>Estatísticas de vendas</h3>
<h4>Vendas este mês</h4>

</div>

<div id='stock-stats-wrapper' class='well' style='display: none'>
<h3>Estatísticas de Estoque</h3>

</div>

@stop

@section('scripts')

$('#vendas-stats').on('click', function(event){
   $('#vendas-stats-wrapper').show('slow');
   $('#stock-stats-wrapper').hide();
});

$('#stock-stats').on('click', function(event){
   $('#vendas-stats-wrapper').hide();
   $('#stock-stats-wrapper').show('slow');
});


@stop