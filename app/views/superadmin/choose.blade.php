@extends('layouts.default')

@section('sidebar')

@stop

@section('content')
	
		<h3>
			Olá, Seja bem vindo!
		</h3>
      <p class="lead">
         Escolha com qual CD gostaria de entrar
      </p>

      <ul class='inline'>
		@foreach ($cds as $cd)
         <li>
            <p>
               <a href="{{ URL::route('superadmin.switch',$cd->id) }}" class='btn pretty-button'>{{$cd->account_name}}</a>
            </p>
         </li>
		@endforeach
      </ul>

	
@stop