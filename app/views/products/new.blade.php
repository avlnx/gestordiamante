@extends('layouts.default')


@section('content')
	<div class='row'>
		<div class='span9'>
		<h1>Adicionar novo produto <br/><small>Utilize o formulário abaixo para adicionar o novo produto</small></h1>

		{{ Form::open(array('url' => 'products/new')) }}
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p class='lead'>Informações gerais</p>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $errors->has('name') ? $errors->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name') }}
		    </p>

		    <p>
		    	{{ Form::label('description', 'Breve Descrição:') }}
		    	{{ $errors->has('description') ? $errors->first('description', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::textarea('description') }}
		    </p>
		</div>

		<div class='span3'>
			<p class='lead'>Categoria</p>
			<p>
		    	{{ Form::label('category_id', 'Categoria:') }}
		    	@if($categories)
			    	{{ Form::select('category_id', $categories) }}
			    @else
			    	<p class='text-warning'>Nenhuma categoria cadastrada</p>
			    @endif
			    <br/>
			    <small>{{ HTML::linkRoute('categories.new', 'Nova Categoria &rarr;', array(), array('class'=>''))}}</small>
		    </p>

	    	<p class='lead'>Informações financeiras</p>
	    	<p>
			    {{ Form::label('price', 'Preço:') }}
			    {{ $errors->has('price') ? $errors->first('price', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('price') }}
		    </p>

		    <p>
			    {{ Form::label('margin', 'Margem de lucro:') }}
			    {{ $errors->has('margin') ? $errors->first('margin', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('margin') }}
		    </p>

		    <p>
			    {{ Form::label('box', 'Quantidade em uma caixa:') }}
			    {{ $errors->has('box') ? $errors->first('box', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('box') }}
		    </p>
    	</div>
		<div class='span3'>
    	<p>
	    	{{ Form::submit('Cadastrar Produto &rarr;', array('class' => 'btn btn-primary btn-large btn-block')) }}
	    </p>

	    {{ Form::close() }}
    </div>
	</div>
    
    
    

@stop