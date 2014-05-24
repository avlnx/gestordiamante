@extends('layouts.default')


@section('content')

<h1>Estatísticas</h1>   

<p class="lead">Escolha uma das visualizações abaixo</p>

<div class="btn-group" data-toggle="buttons-radio">
<ul class="inline">
   <li><button href="#" id='vendas_stats' class='btn btn-small pretty-button' data-toggle="button">Vendas</button></li>
   <li><button href="#" id='stock_stats' class='btn btn-small pretty-button' data-toggle="button">Estoque</button></li>
</ul>
</div>

@stop

@section('scripts')



@stop