@extends('layouts.default')

@section('content')

	<h2>Usuários <small>Visualizando usuários cadastrados</small></h2>
	@if(Auth::user()->is_admin)
		<p>
			<a href="{{ URL::route('users.new') }}" class='btn btn-primary pretty-button'>
			    <i class="icon-white icon-plus-sign"></i>
			    Novo Usuário
			</a>
		</p>
	@endif
	
	<table class='table table-condensed table-hover'>
		<tbody>
	@foreach($users as $user)
		<tr>
			<td><p class='lead'>{{ $user->name }}</p></td>
			<td><p>{{ $user->email }}</p></td>
			<td>

				@if($user->is_root)
					<span class="label label-inverse">ROOT</span>
				@elseif ($user->is_superadmin)
					<span class="label label-important">SuperAdmin</span>
				@elseif($user->is_admin)
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
					{{ HTML::linkRoute('users.edit','Editar', array($user->id), array('class' => 'btn btn-mini pretty-button'))}}
					{{ HTML::linkRoute('users.delete', 'Deletar!', array($user->id), array('class' => 'btn btn-mini btn-danger pretty-button'))}}
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