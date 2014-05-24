@extends('layouts.default')

@section('content')
	<div class='row'>
		<div class='span12'>

		</div>
	</div>

	<div class='row'>
		<div class='span9'>
      @if(isset($entry_snapshot))
      <h1><small>Lançar pedido de </small>Reposição do estoque</h1>
         {{ Form::open(array('url' => 'snapshots/new/entry')) }}
      @elseif(isset($baixa_snapshot))
        <h1><small>Registrar </small>Baixa no Estoque</h1>
           {{ Form::open(array('url' => 'snapshots/new/baixa')) }}
      @else
        <h1><small>Adicionar nova fotografia do </small>Estoque atual</h1>
      @endif

      <div class="btn-group" data-toggle="buttons-radio">
         <ul class="inline">
            <li><button type="button" class="btn btn-small btn active pretty-button btn-primary" data-toggle="button" id="ambos">Ambos</button></li>
            <li><button type="button" class="btn btn-mini pretty-button" data-toggle="button" id="virtual-apenas">Virtual apenas</button></li>
            <li><button type="button" class="btn btn-mini pretty-button" data-toggle="button" id="real-apenas">Real apenas</button></li>
            <li><input type='hidden' id='stock_option' value='ambos' name='stock_option' /></li>
         </ul>
      </div>
			

			@include('includes.products_list')

  	</div>
  	<div class='span3'>
  		<div>
    		<h1><small>Total: </small><span id='total'>0</span></h1>
    			<hr/>
    		@if(isset($entry_snapshot))
					{{ Form::submit('Gravar Pedido de Reposição', array('class' => 'btn btn-primary pretty-button'))}}
				@else
					{{ Form::submit('Registrar Baixa do Estoque', array('class' => 'btn btn-primary pretty-button'))}}
				@endif


	    	{{ Form::close() }}
    	</div>
  	</div>
	</div>

@stop

@section('scripts')
<?php @parent ?>
$('#ambos').on('click', function(event){
   $('#stock_option').val('ambos');
   console.log($('#stock_option').val());
});
$('#virtual-apenas').on('click', function(event){
   $('#stock_option').val('virtual');
   console.log($('#stock_option').val());
});
$('#real-apenas').on('click', function(event){
   $('#stock_option').val('real');
   console.log($('#stock_option').val());
});

@stop
