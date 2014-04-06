@extends('layouts.default')


@section('content')

	<h2>Categorias <small>Categorias cadastradas</small></h2>
	@if(Auth::user()->is_admin)
	<p>{{ HTML::linkRoute('categories.new', "Nova Categoria" , [], array('title' => 'Criar nova categoria', 'class' => 'btn')) }}</p>
	@endif
	<hr/>


	<table class='table table-hover table-condensed'>
		<thead>
			<tr>
				<th>Nome</th>
				<th>Descrição</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			@foreach($categories as $category)
				<tr>
					<td>
						<span class='badge badge-info'>{{ $category->num_products() }}</span>
						{{ HTML::linkRoute('categories.focus', $category->name , $category->id , array('title' => $category->description)) }}
						
					</td>
					<td>{{ $category->description }}</td>
					<td>
						@if(Auth::user()->is_admin)
							{{ HTML::linkRoute('categories.edit', 'Editar', $category->id , array('class' => 'btn btn-mini')) }}
							{{ HTML::linkRoute('categories.delete', 'Deletar!', $category->id , array('class' => 'btn btn-mini btn-danger')) }}
						@endif
					</td>
				</tr>
				
			@endforeach

			@if(count($categories)==0)
				<tr><td><p class='text-info'>Nenhuma categoria cadastrada</p></td></tr>
			@endif
		</tbody>
	</table>


	

@stop