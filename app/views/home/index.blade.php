@extends('layouts.default')

@section('content')

	<div class='row'>
		<div class='span9'>
			<h2>Olá! <small>O que você quer fazer hoje?</small></h2>
			<hr/>
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p class='lead'>Vendas</p>
			<p>{{ HTML::linkRoute('sales.new', 'Novo pedido', array(), array('class' => 'btn btn-large btn-primary')) }}</p><br/>
			@if(Auth::check() && Auth::user()->is_admin)
				<p class='lead'>Estoque</p>
				<div class="btn-group btn-group-vertical">
					{{ HTML::linkRoute('snapshots.index', 'Alterações no Estoque', array(), array('class' => 'btn')) }}
					{{ HTML::linkRoute('snapshots.new', 'Nova Fotografia do Estoque', array(), array('class' => 'btn')) }}
					{{ HTML::linkRoute('snapshots.new', 'Nova Fotografia de Pedido de Reposição', array('entry'), array('class' => 'btn')) }}
				</div>
			@endif
		</div>
		
		<div class='span3'>
			<p class='lead'>Produtos</p>
			<div class="btn-group btn-group-vertical">
    			{{ HTML::linkRoute('products.index', 'Lista de Produtos', array(), array('class' => 'btn')) }}
    			@if(Auth::check() && Auth::user()->is_admin)
    				{{ HTML::linkRoute('products.new', 'Novo produto', array(), array('class' => 'btn')) }}
    			@endif
    		</div><br/><br/>
    		<p class='lead'>Categorias</p>
    		<div class="btn-group btn-group-vertical">
    			{{ HTML::linkRoute('categories.index', 'Lista de Categorias', array(), array('class' => 'btn')) }}
    			@if(Auth::check() && Auth::user()->is_admin)
    				{{ HTML::linkRoute('categories.new', 'Nova Categoria', array(), array('class' => 'btn')) }}
    			@endif
    		</div>
		</div>

		<div class='span3'>
			@if(Auth::check() && Auth::user()->is_admin)
				<p class='lead'>Administração</p>
	    		<div class="btn-group btn-group-vertical">
	    			{{ HTML::linkRoute('categories.index', 'Configurações e Opções', array(), array('class' => 'btn btn-small')) }}
	    			{{ HTML::linkRoute('users.index', 'Usuários', array(), array('class' => 'btn btn-small')) }}
	    		</div>
    		@endif
		</div>
	</div>

@stop