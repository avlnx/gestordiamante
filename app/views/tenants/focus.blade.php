@extends('layouts.default')

@section('content')
	<h1><small>Visualizando</small> {{ $tenant->account_name }}</h1>
	<hr/>
	<table class='table table-condensed table-hover'>
		<thead>
			<tr>
				<th>Email</th>
				<th>Membro há</th>
				<th>Empresa</th>
				<th>Produtos</th>
				<th>Categorias</th>
				<th>Usuários</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td>{{ $tenant->email }}</td>
					<td>{{ Ago::agolize($tenant->created_at) }}</td>
					<td>{{ Str::title($tenant->company) }}</td>
					<td><span class='badge badge-info'>{{ $tenant->products()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->categories()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->users()->count() }}</span></td>
				</tr>
		</tbody>
	</table>

	

@stop