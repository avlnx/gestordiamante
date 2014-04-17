@extends('layouts.default')

@section('sidebar')

@stop

@section('content')
	
		<h3>
			Olá! <small>Escolha com qual CD gostaria de entrar</small>
		</h3>

		@foreach ($cds as $cd)
			<p>{{ link_to_route('superadmin.switch', $cd->account_name, $parameters = array($cd->id), $attributes = array('class' => 'btn btn-large')) }}</p>
		@endforeach

	
@stop