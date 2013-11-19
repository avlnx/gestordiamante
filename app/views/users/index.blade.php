@extends('layouts.default')

@section('content')

	<h2>Usuários <small>Usuários cadastrados</small></h2>
	{{ HTML::link_to_route('users.new', "Novo Usuário" ,'', array('title' => 'Criar novo usuário', 'class' => 'btn btn-large btn-primary')) }}
	<hr/>

	<table class='table table-condensed table-hover'>
		<tbody>
	@foreach($users as $user)
		<tr>
			<td><p class='lead'>{{ $user->name }}</p></td>
			<td><p>{{ $user->email }}</p></td>
			<td>
				@if($user->is_root)
					<span class="label label-inverse">ROOT</span>
				@endif
				@if($user->is_admin)
					<span class="label label-warning">Admin</span>
				@else
					<span class="label">Usuário comum</span>
				@endif
			</td>
			<td>
				<p>Membro há <small>{{ Ago::agolize($user->created_at) }}</small> </p>
			</td>
			<td>
				<p>
					{{ HTML::link_to_route('users.edit','Editar', array($user->id), array('class' => 'btn btn-mini'))}}
					{{ HTML::link_to_route('users.delete', 'Deletar!', array($user->id), array('class' => 'btn btn-mini btn-danger'))}}
				</p>
			</td>

		</tr>

	@endforeach
	@if(count($users)==0)
			<tr><td colspan='5'>Nenhum usuário cadastrado.</td></tr>
	@endif
			</tbody>
	</table>
	

@stop