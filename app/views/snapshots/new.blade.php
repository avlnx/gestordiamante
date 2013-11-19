@extends('layouts.default')

@section('content')
	<div class='row'>
		<div class='span9'>

		@if(isset($entry_snapshot))
			<h1><small>Adicionar </small>Pedido recebido para repor estoque do CD</h1>
		@elseif(isset($baixa_snapshot))
			<h1><small>Adicionar nova </small>Baixa do Estoque</h1>
		@else
			<h1><small>Adicionar nova fotografia do </small>Estoque atual</h1>
		@endif

		<hr/>
		</div>	
	</div>

	<div class='row'>
		<div class='span6'>

			@if(isset($entry_snapshot))
				{{ Form::open(array('url' => 'snapshots/new/entry')) }}
			@elseif(isset($baixa_snapshot))
				{{ Form::open(array('url' => 'snapshots/new/baixa')) }}
			@else
				{{ Form::open(array('url' => 'snapshots/new')) }}
			@endif

			@include('includes.products_list')


			

			
    	</div>
    	<div class='span3'>
    		<div class='sb-fixed'>
	    		<h1><small>Total: </small>R$ <span id='total'>0</span></h1>
	    			<hr/>
	    		@if(isset($entry_snapshot))
					{{ Form::submit('Gravar Pedido de Reposição &rarr;', array('class' => 'btn btn-primary btn-large'))}}
				@elseif(isset($baixa_snapshot))
					{{ Form::submit('Registrar Baixa do Estoque &rarr;', array('class' => 'btn btn-primary btn-large'))}}
				@else
					{{ Form::submit('Gerar Fotografia do Estoque &rarr;', array('class' => 'btn btn-primary btn-large'))}}
				@endif


		    	{{ Form::close() }}
	    	</div>
    	</div>	
	</div>

@stop