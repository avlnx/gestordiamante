<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestor Diamante</title>
	<link rel="shortcut icon" href="img/favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/bootstrap-responsive.min.css') }}
	{{ HTML::style('css/datepicker.css') }}
	{{ HTML::style('css/my-styles.css') }}
	{{-- HTML::style('css/smoothness/jquery-ui.custom.min.css') --}}

</head>
<body >
<div class="loader" style='display:none'></div>
@if (Auth::check())
<div class="navbar">
	<div class="navbar-inner navbar-fixed-top">
		<div class="container">
			<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
	      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </a>
	      <ul class="nav">
	      	<li>
	      		<p class='navbar-text' style='padding-left: 0px;'>
	      			<span class='label label-info'> <i class='icon-certificate icon-white'></i> GESTOR DIAMANTE</strong></span>
	      		</p>
	      	</li>
	      </ul>

	      <div class="nav-collapse collapse">
			<ul class="nav">

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon icon-shopping-cart'></i> Vendas <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('sales.new') }}">Nova Venda</a></li>
						<li>{{ HTML::linkRoute('sales.asindex','Últimas vendas') }}</li>
					</ul>
			  	</li>


				
				
				@if (Auth::user()->is_admin)


				<li class='divider-vertical'></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon icon-inbox'></i> Estoque <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
						<li>{{ HTML::linkRoute('snapshots.index','Histórico do Estoque') }}</li>
						<li class='divider'></li>
					   <li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
						<li>{{ HTML::linkRoute('snapshots.new','Registrar Baixa no Estoque', array('baixa')) }}</li>
					</ul>
			  	</li>

			  	<li class='divider-vertical'></li>

			  	<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon icon-wrench'></i> Administração <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>{{ HTML::linkRoute('stats.index','Visualizar Estatísticas e Relatórios') }}</li>
						<li class='divider'></li>
						<li>{{ HTML::linkRoute('products.admin','Administrar Produtos e Categorias') }}</li>
					   
					   <li class='divider'></li>
					   <li>{{ HTML::linkRoute('users.index','Administrar Usuários', array(), array('role' => 'menuitem')) }}</li>
					</ul>
			  	</li>

			  	{{--@if (Auth::user()->is_root)--}}
			  	<li class='divider-vertical'></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon icon-star'></i> Root <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>{{ HTML::linkRoute('tenants.index','Visualizar Clientes') }}</li>
						<li><a href="{{route('tenants.new')}}">Novo Cliente</a></li>
						<li><a href="{{ route('tenants.update_from_model')}}">Atualizar Modelos</a></
					</ul>
			  	</li>
				{{--@endif--}}
				
				@endif


				</div>
			</ul>
		</div>
	</div>
</div>
@endif

<?php if(isset($container_wrapper)){$container_wrapper = false;} else {$container_wrapper = true;} ?>
<div class='container @if($container_wrapper)shadow-container main-container@endif'>
	@if (Auth::check())
	<div class="row">
		<div class="span12" style="background-color: white;height: 40px;padding-top: 10px;border-bottom: 1px solid #eee;">
			<ul class="inline">
			  	<li>
		  			<small>Olá <strong>{{ Auth::user()->email }}</strong>
		  				{{ HTML::linkRoute('account.logout', 'Sair?', array(), array('class'=>'small')) }}
		  			</small>
			  	</li>

				@if (Auth::user()->is_superadmin)
					<li>
						<span class='label label-important'><strong>{{ Auth::user()->tenant->account_name }}</strong></span>
						<small>{{ HTML::linkRoute('superadmin.choose','Alterar?') }}</small>
					</li>
				@endif

			</ul>
		</div>
	</div>
	@endif

	<div class='row' style="padding-top: 10px; z-index: 10000000">
		<div class='span12'>
			@if (Session::get('notice'))
				<div class="alert alert-info">
			    	<button type="button" class="close" data-dismiss="alert">&times;</button>
			    	{{ Session::get('notice') }}
			    </div>
			@endif

			@if (Session::get('error'))
			    <div class="alert alert-error">
			    	<button type="button" class="close" data-dismiss="alert">&times;</button>
			    	<strong>Ops!</strong> {{ Session::get('error') }}
			    </div>

			@endif
		</div>

	</div>

	<div class='row'>
		<div class="span12">
			@yield('content')
			</div>
		</div>
	</div>


</div>
	{{-- HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js') --}}
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/bootstrap-datepicker.js') }}
	{{ HTML::script('js/jquery.currency.js') }}
	{{ HTML::script('js/jquery.jstepper.js') }}
	{{--HTML::script('js/jquery-ui.custom.min.js')--}}

	<script type='text/javascript'>
		@yield('script_functions')

		$(document).ready(function() {
			$('.dropdown-toggle').dropdown();
			$('.confirm').on('click', function(event){
				if(!confirm('Tem certeza de que deseja *'+this.innerHTML+'* ?'))
				{
					event.preventDefault();
				}
			});
			@yield('scripts')
			@yield('scripts2')
			// Fix formating of money numbers
			$('.currency').currency({
				region: 	'BRL',
				thousands: '.',
				decimal: ','
			});
			$('.tooltip-trigger').tooltip({});

			/*
			$('.qtdd,.int-field').jStepper({
				minValue:			0,
				defaultValue:		0,
				allowDecimals:		false
			});
			*/
			$('.qtdd,.int-field,.float-field').on('blur', function(e){
				if($(this).val() == '') {
					$(this).val(0);
				}
			});
			/*
			$('.float-field').jStepper({
				minValue:			0,
				defaultValue:		0,
				allowDecimals:		true,
				decimalSeparator: ',',
				minDecimals: 		0,
				maxDecimals: 		2
			});
			*/
			//
			/* 
			$('.action-button').click(function(event){
				$(this).text('Carregando...');
				$(this).prop('disabled', true);
			});
			*/
 		});

	</script>

</body>
</html>
