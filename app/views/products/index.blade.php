@extends('layouts.default')


@section('content')

	<h2>Produtos <small>Produtos cadastrados</small></h2>
	@if(Auth::user()->is_admin)
	{{ HTML::link_to_route('products.new', "Novo Produto" ,'', array('title' => 'Criar novo produto', 'class' => 'btn btn-large btn-primary')) }}
	@endif
	<hr/>

	<p class='lead'>Clique sobre uma categoria para visualizar os produtos</p>
	@foreach($categories as $category)

		<a class='no-format' id='table-toggler-{{ $category->id }}' href='#'><p class='lead'>{{ $category->name }}</p></a>

		<div id='table-wrapper-{{ $category->id }}' style='display:none'>
		<table class='table table-hover table-condensed'>
			<thead>
				<tr>
					<th>Nome</th><th>Descrição</th><th>Preço</th><th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php $found = False ?>
				@foreach($products as $product)

					@if($product->category_id == $category->id)
						<?php $found = True ?>
						<tr>
							<td>
								{{ HTML::link_to_route('products.focus', $product->name , array($product->id), array('title' => $product->description)) }}
							</td>
							<td>
								{{ $product->description }}
							</td>
							<td>
								R$ {{ $product->price }}
							</td>	
							<td>
								@if(Auth::user()->is_admin)
									{{ HTML::link_to_route('products.edit', 'Editar', array($product->id), array('class' => 'btn btn-mini'))}}
									{{ HTML::link_to_route('products.delete', 'Deletar!', array($product->id), array('class' => 'btn btn-mini btn-danger'))}}
								@endif
							</td>
						</tr>
					@endif
				@endforeach
				@if(!$found)
					<tr><td colspan='3'><p class='text-info'>Nenhum produto nesta categoria</p></td></tr>
				@endif
			</tbody>
		</table>
		</div>

	@endforeach
	@if(count($categories)==0)
		<p>Nenhum produto cadastrado</p>
	@endif

@stop

@section('scripts')
	@foreach($categories as $category)
		$('#table-toggler-{{ $category->id }}').on('click', function(event){
			event.preventDefault();
			$('#table-wrapper-{{ $category->id }}').slideToggle();
		});
	@endforeach
@stop