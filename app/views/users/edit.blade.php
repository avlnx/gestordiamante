@extends('layouts.default')

@section('content')
	<div class='row'>
		<div class='span9'>
		<h1><small>Atualizando</small> {{ $user->name }}</h1>

		{{ Form::open(array('url' => 'users/edit/'.$user->id)) }}
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p class='lead'>Dados Pessoais</p>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $messages()->has('name') ? $messages()->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name', $user->name) }}
		    </p>
		    <p>
		    	{{ Form::label('email', 'Email:') }}
		    	{{ $messages()->has('email') ? $messages()->first('email', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::text('email', $user->email) }}
		    </p>

		</div>

		<div class='span3'>
			<p class='lead'>Segurança</p>
			<p>
		    	{{ Form::label('password', 'Senha:') }}
		    	{{ $messages()->has('password') ? $messages()->first('password', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::text('password') }}
		    </p>
		    <p>
		    	{{ Form::radio('is_admin', True, $user->is_admin) }} <span class="label label-warning">Admin</span><br/>
		    	{{ Form::radio('is_admin', False, !$user->is_admin) }} <span class="label">Usuário comum</span>
		    </p>
		</div>

		<div class='span3'>
    	<p>
	    	{{ Form::submit('Atualizar Usuário &rarr;', array('class' => 'btn btn-primary btn-large btn-block')) }}
	    </p>

	    {{ Form::close() }}
    </div>
	</div>
    
    
    

@stop