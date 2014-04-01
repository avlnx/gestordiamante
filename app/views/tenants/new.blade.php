@extends('layouts.default')

@section('content')
	<h1>Adicionando novo <em>Tenant</em></h1>
	<hr/>

	{{ Form::open(array('url' => 'tenants/new')) }}

	<p class='lead'>Informações Gerais</p>

	<p>
		{{ Form::label('email', 'Email da conta Administrador:') }}
		{{ $errors->has('email') ? $errors->first('email', '<p class="text-error">:message</p>') : '' }}
	    
	    {{ Form::text('email') }}
    </p>
    <p>
    	{{ Form::label('password', 'Senha da conta Administrador:') }}
    	{{ $errors->has('password') ? $errors->first('password', '<p class="text-error">:message</p>') : '' }}
    	{{ Form::text('password') }}
    </p>

    <p>
		<span class='label label-info'>Modelo?</span>
		{{ $errors->has('is_model') ? $errors->first('is_model', '<p class="text-error">:message</p>') : '' }}
	    
	    {{ Form::checkbox('is_model', 'true'); }}
    </p>

    <p>
		{{ Form::label('company', 'Empresa:') }}
		{{ $errors->has('company') ? $errors->first('company', '<p class="text-error">:message</p>') : '' }}

	    {{ Form::select('company', Config::get('my_config.companies')); }}
    </p>
    <hr/>

    <p>
    	{{ Form::submit('Cadastrar Tenant &rarr;', array('class' => 'btn btn-primary btn-large')) }}
    </p>
	
	{{ Form::close() }}

@stop