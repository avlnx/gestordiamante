@extends('layouts.default')

@section('content')
	<h1>Adicionando novo <em>Cliente</em></h1>
	<hr/>

	{{ Form::open(array('url' => 'tenants/new')) }}

	<p class='lead'>Informações Gerais</p>
   <p>
      {{ Form::label('account_name', 'Nome do CD:') }}
      {{ $errors->has('account_name') ? $errors->first('account_name', '<p class="text-error">:message</p>') : '' }}
       
       {{ Form::text('account_name') }}
    </p>
    <hr>
    <p>
        <strong>Escolha um superadmin existente ou crie um novo:</strong><br>
        {{ Form::select('superadmin_id', $superadmins_array) }}
		{{ Form::label('email', 'Email da nova conta SuperAdmin:') }}
		{{ $errors->has('email') ? $errors->first('email', '<p class="text-error">:message</p>') : '' }}
	    
	    {{ Form::text('email') }}
    </p>
    <p>
    	{{ Form::label('password', 'Senha da nova conta SuperAdmin:') }}
    	{{ $errors->has('password') ? $errors->first('password', '<p class="text-error">:message</p>') : '' }}
    	
        <input type='password' name='password' id='password' />
    </p>
    <p>
        {{ Form::label('password_confirm', 'Confirme a senha:') }}
        <input type='password' name='password_confirm' id='password_confirm' />
        <div class="alert" id='password-alert' style='display:none'>
            <strong>Ops!</strong> As senhas não conferem. A senha e a confirmação devem ser iguais. Tente novamente.
        </div>
    </p>
    <hr>

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
        <button type='submit' class='btn btn-primary pretty-button' id='cadastrar-submit'><i class='icon icon-white icon-ok'></i> Cadastrar Tenant</button>
    </p>
	
	{{ Form::close() }}

@stop

@section('scripts')
    $('#password_confirm').on('keyup', function(e) {
        pc = $(this).val();
        p = $('#password').val();
        if(p != pc) {
            // passwords dont match
            //$(this).val('');
            $('#password-alert').show();
            $('#password_confirm').focus();
        } else {
            // all good
            $('#password-alert').hide();
        }
    });
    $('#cadastrar-submit').on('click', function(e) {
        if($('#password-alert').is(':visible')) {
            alert('Por favor, corrija a senha.');
            e.preventDefault();
        } else {
            $(this).addClass('disabled');
        }
    });
@stop