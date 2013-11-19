@extends('layouts.default')

@section('content')
		<h3>{{ $category->name }}</h3>
		<p class='lead'>{{ $category->description }}</p>
		<p>
			<span class="badge badge-info">{{ $num_products }}</span> Produtos cadastrados nessa categoria.
		</p>
		<p><small><em>Categoria criada há {{ Ago::agolize($category->created_at) }}</em></small></p>
	
@stop