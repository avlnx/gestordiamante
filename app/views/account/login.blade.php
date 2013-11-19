@extends('layouts.default')

@section('sidebar')

@stop

@section('content')
	
		<h3>Olá!</h3>

		<h4 class='lead'>Entre com seu email e senha:</h4>
		<hr/>

		    {{ Form::open(array('url' => 'account/check')) }}

		    @if (Session::has('login_errors'))
		    	<p class="text-error">Nome de usuário e/ou senha inválidos. Tente novamente.</p>
		    @endif

		    <p>
			    {{ Form::label('email', 'Email') }}
			    {{ Form::text('email') }}
		    </p>

		    <p>
		    	{{ Form::label('password', 'Senha') }}
		    	{{ Form::password('password') }}
		    </p>

		    <p>
		    	{{ Form::submit('Entrar', array('class' => 'btn btn-primary')) }}
		    </p>

		    {{ Form::close() }}


	
@stop