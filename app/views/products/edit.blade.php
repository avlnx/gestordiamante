@extends('layouts.default')


@section('content')
@include('includes.back-button', array('route' => URL::route('products.admin')))
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
				{{ Form::label('ref', 'Código:') }}
				{{ $errors->has('ref') ? $errors->first('ref', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('ref', $product->ref) }}
		    </p>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $errors->has('name') ? $errors->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name', $product->name) }}
		    </p>

		    <p>
		    	{{ Form::label('description', 'Breve Descrição:') }}
		    	{{ $errors->has('description') ? $errors->first('description', '<p class="text-error">:message</p>') : '' }}
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
			    <small>{{ HTML::linkRoute('categories.new', 'Nova Categoria &rarr;', array(), array('class'=>''))}}</small>
		    </p>

	    	<p class='lead'>Informações financeiras</p>
	    	<p>
			    {{ Form::label('price', 'Preço:') }}
			    {{ $errors->has('price') ? $errors->first('price', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('price', $product->price) }}
		    </p>

		    <p>
			    {{ Form::label('margin', 'Margem de lucro:') }}
			    {{ $errors->has('margin') ? $errors->first('margin', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('margin', $product->margin) }}
		    </p>

		    <p>
			    {{ Form::label('box', 'Quantidade em uma caixa:') }}
			    {{ $errors->has('box') ? $errors->first('box', '<p class="text-error">:message</p>') : '' }}
			    {{ Form::text('box', $product->box) }}
		    </p>
    	</div>
		<div class='span3'>
	    	<p>
	    		<button type="submit" class='btn btn-primary pretty-button'>
	    			<i class='icon-white icon-edit'></i>
	    			Atualizar Produto
	    		</button>
		    </p>
		    

		    {{ Form::close() }}
    	</div>
	</div>
    
    
    

@stop