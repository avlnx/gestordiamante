@extends('layouts.default')

@section('content')

	<h1>Olá Thyago <small>como vai hoje?</small></h1>

	<hr/>

	<ul class='inline'>
		<li><a href="{{ route('tenants.new')}}" class='btn btn-primary pretty-button'><i class='icon icon-white icon-plus-sign'></i> Novo Cliente</a></li>
		<li><a href="{{ route('tenants.update_from_model')}}" class='btn pretty-button'><i class='icon icon-refresh'></i> Atualizar Modelos</a></li>
	</ul>

	<hr/>

	<p><a href="#" class='btn btn-mini pretty-button' id='tenants-table-switch'>Visualizar Clientes</a></p>
	
	<table class='table table-condensed table-hover' id='tenants-table' style='display:none'>
		<thead>
			<tr>
				<th>Nome</th>
				<th>Superadmin</th>
				<th>Membro há</th>
				<th>Empresa</th>
				<th>Produtos</th>
				<th>Categorias</th>
				<th>Usuários</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($tenants as $tenant)
				<tr>
					<td><span class="label {{$tenant->label}}">{{ $tenant->account_name }}</span></td>
					<td>{{ HTML::linkRoute('tenants.focus', $tenant->email, array($tenant->id)) }}</td>
					<td>{{ Ago::agolize($tenant->created_at) }}</td>
					<td>{{ Str::title($tenant->company) }}</td>
					<td><span class='badge badge-info'>{{ $tenant->products()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->categories()->count() }}</span></td>
					<td><span class='badge badge-info'>{{ $tenant->users()->count() }}</span></td>
					<td>{{ HTML::linkRoute('tenants.delete', 'Deletar?', array($tenant->id), array('class' => 'btn btn-danger btn-mini confirm')) }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@stop

@section('scripts')
	$('#tenants-table-switch').on('click', function(e) {
		if($('#tenants-table').is(':visible')) {
			// hide and text = Visualizar Clientes
			$('#tenants-table').fadeOut();
			$('#tenants-table-switch').text('Visualizar Clientes');
		} else {
			// show and text = Esconder Clientes
			$('#tenants-table').fadeIn();
			$('#tenants-table-switch').text('Esconder Clientes');
		}
		e.preventDefault();
	});
@stop