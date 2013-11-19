@extends('layouts.default')


@section('content')
	<div class='row'>
		<div class='span9'>
		<h1><small>Atualizando</small> {{ $product->name }} </h1>

		{{ Form::open(array('url' => 'products/edit/'.$product->id)) }}
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p class='lead'>Informações gerais</p>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $messages()->has('name') ? $messages()->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name', $product->name) }}
		    </p>

		    <p>
		    	{{ Form::label('description', 'Breve Descrição:') }}
		    	{{ $messages()->has('description') ? $messages()->first('description', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::textarea('description', $product->description) }}
		    </p>
		</div>

		<div class='span3'>
			<p class='lead'>Categoria</p>
			<p>
		    	{{ Form::label('category_id', 'Categoria:') }}
		    	<?php //print_r($product->category_id) ?>
		    	@if($categories)
			    	{{ Form::select('category_id', $categories, $product->category_id) }}
			    @else
			    	<p class='text-warning'>Nenhuma categoria cadastrada</p>
			    @endif
			    <br/>
			    <small>{{ HTML::link_to_route('categories.new', 'Nova Categoria &rarr;', array(), array('class'=>''))}}</small>
		    </p>

	    	<p class='lead'>Informações financeiras</p>
	    	<p>
			    {{ Form::label('price', 'Preço:') }}
			    {{ $messages()->has('price') ? $messages()->first('price', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('price', $product->price) }}
		    </p>

		    <p>
			    {{ Form::label('margin', 'Margem de lucro:') }}
			    {{ $messages()->has('margin') ? $messages()->first('margin', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('margin', $product->margin) }}
		    </p>

		    <p>
			    {{ Form::label('box', 'Quantidade em uma caixa:') }}
			    {{ $messages()->has('box') ? $messages()->first('box', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('box', $product->box) }}
		    </p>
    	</div>
		<div class='span3'>
	    	<p>
		    	{{ Form::submit('Atualizar Produto &rarr;', array('class' => 'btn btn-primary btn-large btn-block')) }}
		    </p>
		    <p>
		    	{{ HTML::link_to_route('products.index', 'Cancelar &times;', array(), array('class' => 'btn'))}}

		    {{ Form::close() }}
    	</div>
	</div>
    
    
    

@stop