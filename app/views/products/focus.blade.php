@extends('layouts.default')


@section('content')
	<h3>{{ $product->name }}</h3>
	<p class='lead'>{{ $product->description }}</p>
	<p>
		Cadastrado em
		{{ HTML::link_to_route('categories.focus', $product->category->name, array($product->category->id)) }}
		Há <small>{{ Ago::agolize($product->created_at)}}</small>
	</p>
	<table class='table'>
		<thead>
			<tr>
				<th>Preço de Venda</th>
				<th>Margem de Lucro</th>
				<th>Unidades por caixa</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong>R${{ $product->price }}</strong></td>
				<td><strong>{{ $product->pretty_margin() }}%</strong></td>
				<td><strong>{{ $product->box }}</strong></td>
		</tbody>
	</table>

	<p><small><em>Última alteração há {{ Ago::agolize($product->updated_at)}}</em></small></p>

	<p>
		{{ HTML::link_to_route('products.edit', 'Editar', array($product->id), array('class' => 'btn btn-primary') )}}
		{{ HTML::link_to_route('products.delete', 'Deletar!', array($product->id), array('class' => 'btn btn-danger btn-small') )}}
	</p>
@stop