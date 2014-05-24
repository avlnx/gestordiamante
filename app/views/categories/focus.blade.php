@extends('layouts.default')

@section('content')
@include('includes.back-button', array('route' => URL::route('products.admin')))
		<h3>{{ $category->name }}</h3>

      <blockquote>
         <p class='lead'>Descrição</p>
         {{ $category->description }}
      </blockquote>
		
      <p>
         <a href="{{ URL::route('products.new') }}" class='btn btn-primary pretty-button'>
             <i class="icon-white icon-plus-sign"></i>
             Novo Produto
         </a>
      </p>
      <hr/>
		<p>
			<span class="badge badge-info">{{ $num_products }}</span> Produtos cadastrados nessa categoria.
		</p>
		<p><small><em>Categoria criada há {{ Ago::agolize($category->created_at) }}</em></small></p>
	
@stop