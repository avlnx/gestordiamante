<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestor Diamante</title>
	<link rel="shortcut icon" href="img/favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/bootstrap-responsive.min.css') }}
	{{ HTML::style('css/datepicker.css') }}

</head>
<body style='padding-top: 23px;'>
	<style type="text/css">
		@media (min-width: 768px) { 
		  .sb-fixed{
		    position: fixed;
		    width: inherit;
		    z-index: 999;
		    /*overflow-y: scroll;*/
		  } 
		}
	</style>
	
@if (Auth::check())
<div class="navbar" style="background: white;">
	<div class="navbar-inner navbar-fixed-top" style="background: white;">
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
	      			<span class='label label-inverse'> <i class='icon-certificate icon-white'></i> GESTOR DIAMANTE</strong></span>
	      		</p>
	      	</li>
	      </ul>
	      
	      <div class="nav-collapse collapse">
			<ul class="nav">
				
				<li>
					<a href="{{ route('sales.new') }}"><strong><i class='icon icon-shopping-cart'></i> Nova Venda</strong></a>

				</li>
				<li>{{ HTML::linkRoute('sales.asindex','Últimas vendas') }}</li>
				<li class='divider-vertical'></li>
				<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
				<li>{{ HTML::linkRoute('snapshots.index','Histórico do Estoque') }}</li>
				
				
				
				</div>
			</ul>
		</div>
	</div>
</div>
@endif



	
<div class='container'>
	@if (Auth::check())
	<div class="row sb-fixed">
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
							    <li>{{ HTML::linkRoute('products.new','Cadastrar Novo Produto') }}</li>
							    <li>{{ HTML::linkRoute('categories.new','Cadastrar Nova Categoria') }}</li>
							    <li class='divider'></li>
							    <li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
							    <li class='divider'></li>
							    <li>{{ HTML::linkRoute('users.index','Usuários', array(), array('role' => 'menuitem')) }}</li>
						    @endif
						    @if (Auth::check() && Auth::user()->is_root)
						    	<li>{{ HTML::linkRoute('tenants.index','Clientes') }}</li>
						    	<li>{{ HTML::linkRoute('tenants.new','Novo Cliente') }}</li>
						    @endif
						  </ul>
						</div>
					</li>
				@endif
			</ul>
		</div>
	</div>
	@endif
	
	<div class='row' style="padding-top: 60px;">
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
	
	<div class='row' style="padding-top: 20px;">
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

	<script type='text/javascript'>
		@yield('script_functions')

		$(document).ready(function() {
			$('.dropdown-toggle').dropdown();
			@yield('scripts')
			@yield('scripts2')
 		});
		
	</script>

</body>
</html>
