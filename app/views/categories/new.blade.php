@extends('layouts.default')

@section('content')
	<div class='row'>
		<div class='span9'>
		<h1>Adicionar nova categoria <br/><small>Utilize o formulário abaixo para adicionar a nova categoria</small></h1>

		{{ Form::open(array('url' => 'products/categories/new')) }}
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $errors->has('name') ? $errors->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name') }}
		    </p>

		</div>

		<div class='span3'>

		    <p>
		    	{{ Form::label('description', 'Breve Descrição:') }}
		    	{{ $errors->has('description') ? $errors->first('description', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::textarea('description') }}
		    </p>
		</div>

		<div class='span3'>
    	<p>
	    	{{ Form::submit('Cadastrar Categoria &rarr;', array('class' => 'btn btn-primary btn-large btn-block')) }}
	    </p>

	    {{ Form::close() }}
    </div>
	</div>
    
    
    

@stop