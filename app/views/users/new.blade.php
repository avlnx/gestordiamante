@extends('layouts.default')

@section('content')
	<div class='row'>
		<div class='span9'>
		<h1>Adicionar novo usuário <br/><small>Utilize o formulário abaixo para adicionar o novo usuário</small></h1>

		{{ Form::open(array('url' => 'users/new')) }}
		</div>	
	</div>

	<div class='row'>
		<div class='span3'>
			<p class='lead'>Dados Pessoais</p>
			<p>
				{{ Form::label('name', 'Nome:') }}
				{{ $messages()->has('name') ? $messages()->first('name', '<p class="text-error">:message</p>') : '' }}
			    
			    {{ Form::text('name') }}
		    </p>
		    <p>
		    	{{ Form::label('email', 'Email:') }}
		    	{{ $messages()->has('email') ? $messages()->first('email', '<p class="text-error">:message</p>') : '' }}
		    	{{ Form::text('email') }}
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
		    	{{ Form::radio('is_admin', True) }} <span class="label label-warning">Admin</span><br/>
		    	{{ Form::radio('is_admin', False, true) }} <span class="label">Usuário comum</span>
		    </p>
		</div>

		<div class='span3'>
    	<p>
	    	{{ Form::submit('Cadastrar Usuário &rarr;', array('class' => 'btn btn-primary btn-large btn-block')) }}
	    </p>

	    {{ Form::close() }}
    </div>
	</div>
    
    
    

@stop