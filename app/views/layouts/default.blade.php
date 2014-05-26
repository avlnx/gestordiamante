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

</head>
<body >

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

				<li>
					<a href="{{ route('sales.new') }}"><strong><i class='icon icon-shopping-cart'></i> Nova Venda</strong></a>

				</li>
				<li>{{ HTML::linkRoute('sales.asindex','Últimas vendas') }}</li>
				@if (Auth::user()->is_superadmin)
				<li class='divider-vertical'></li>
				<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
				<li>{{ HTML::linkRoute('snapshots.index','Histórico do Estoque') }}</li>
				<li class='divider-vertical'></li>
				<li>{{ HTML::linkRoute('stats.index','Estatísticas e Relatórios') }}</li>
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
						<span class='label label-default'><strong>{{ Auth::user()->tenant->account_name }}</strong></span>
						<small>{{ HTML::linkRoute('superadmin.choose','Alterar?') }}</small>
					</li>
				@endif

				@if (Auth::user()->is_admin)
					<li>
						<div class="btn-group">
						  <a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
						    <i class="icon-wrench"></i>
						    <span class="caret"></span>
						  </a>
						  <ul class="dropdown-menu">
						    @if(Auth::check() && Auth::user()->is_admin)
                              <li>{{ HTML::linkRoute('products.admin','Administrar Produtos e Categorias') }}</li>
							    <li class='divider'></li>
							    <li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
									<li>{{ HTML::linkRoute('snapshots.new','Registrar Baixa no Estoque', array('baixa')) }}</li>
							    <li class='divider'></li>
							    <li>{{ HTML::linkRoute('users.index','Administrar Usuários', array(), array('role' => 'menuitem')) }}</li>
						    @endif
						    @if (Auth::check() && Auth::user()->is_root)
						    	<li class='divider'></li>
						    	<li>{{ HTML::linkRoute('tenants.index','Clientes') }}</li>
						    @endif
						  </ul>
						</div>
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

			$('.qtdd,.int-field').jStepper({
				minValue:			0,
				defaultValue:		0,
				allowDecimals:		false
			});
			$('.qtdd,.int-field,.float-field').on('blur', function(e){
				if($(this).val() == '') {
					$(this).val(0);
				}
			});
			$('.float-field').jStepper({
				minValue:			0,
				defaultValue:		0,
				allowDecimals:		true,
				decimalSeparator: ',',
				minDecimals: 		0,
				maxDecimals: 		2
			});
 		});

	</script>

</body>
</html>
