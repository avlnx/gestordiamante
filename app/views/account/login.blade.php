@extends('layouts.default', array('container_wrapper' => false))

@section('sidebar')

@stop

@section('content')
	
	{{ Form::open(array('url' => 'account/check','class'=> 'form-signin')) }}
		<span class='label label-inverse'> <i class='icon-certificate icon-white'></i> GESTOR DIAMANTE</strong></span>
     	<h2 class="form-signin-heading">Seja bem vindo</h2>
     	<input type="text" class="input-block-level" name="email" placeholder="Email">
     	<input type="password" class="input-block-level" name="password" placeholder="Senha">
     	<!--<label class="checkbox">
      	<input type="checkbox" value="remember-me"> Remember me
     	</label>-->
     	<button class="btn btn-large btn-primary" type="submit"><i class="icon-lock icon-white"></i> Entrar</button>
	{{ Form::close() }}
	
<style type="text/css">
body
{
	background-color: #eee;
}
.form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
</style>		


	
@stop