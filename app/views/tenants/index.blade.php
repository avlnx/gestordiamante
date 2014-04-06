@extends('layouts.default')

@section('content')
	<h1>Hello Thyago <small>these are the tenants:</small></h1>
	{{ HTML::linkRoute('tenants.new', 'Novo Tenant', [], array('class'  => 'btn'))}}
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
			@foreach ($tenants as $tenant)
				<tr>
					<td>{{ HTML::linkRoute('tenants.focus', $tenant->email, array($tenant->id)) }}</td>
					<td>{{ Ago::agolize($tenant->created_at) }}</td>
					<td>{{ Str::title($tenant->company) }}</td>
					<td><span class='badge badge-info'>{{ $tenant->products()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->categories()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->users()->count() }}</span></td>
				</tr>
			@endforeach
		</tbody>
	</table>

@stop