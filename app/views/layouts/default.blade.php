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
<body style='background: #fefefe;padding-top: 30px;'>
	<style type="text/css">
		@media (min-width: 768px) { 
		  .sb-fixed{
		    position: fixed;
		    width: inherit;
		    /*overflow-y: scroll;*/
		  } 
		}
	</style>
	
<div class='container'>
	@if (Auth::check())
	<div class="navbar">
		<div class="navbar-inner navbar-fixed-top">
			<ul class="nav">
				<li>
					<a href="{{ route('sales.new') }}"><strong><i class='icon icon-shopping-cart'></i> Nova Venda</strong></a>

				</li>
				<li>{{ HTML::linkRoute('sales.asindex','Últimas vendas') }}</li>
				<li class='divider-vertical'></li>
				<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
				<li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
				@if (Auth::user()->is_superadmin)
					<li class='divider-vertical'></li>
					<li>
						<p class='navbar-text'>
							<span class='label label-inverse'>Logado como <strong>{{ Auth::user()->tenant->account_name }}</strong></span> 
							<small>{{ HTML::linkRoute('superadmin.choose','Alterar') }}</small>
						</p>
						</li>
				@endif
			</ul>
		</div>
	</div>
	@endif
	

	<div class='row'>
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
		<div class='span3 '>
			<div class="header-container sb-fixed" style='margin-bottom: 0px;'>
				{{ HTML::image('img/logo-tiny.png', 'Gestor Diamante', array('class' => '', 'style' => 'float:left'))}}
				<h4 class='text-left' style=''>GESTOR DIAMANTE<br/>
				<small>TOME O CONTROLE DO SEU CD</small></h4>
				<p class='text-left' style='margin-top: 5px'>
					@if (Auth::check())
						<small>Olá <strong>{{ Auth::user()->email }}</strong>
						{{ HTML::linkRoute('account.logout', 'Sair?', array(), array('class'=>'btn btn-danger btn-mini')) }}
						</small>
					@else
						<small>
							{{ HTML::linkRoute('account.login', 'Entrar', array(), array('class'=>'btn btn-primary btn-mini')) }}
						</small>
					@endif
				</p>
				<br class='clearfix'/><br/>
			

				@if (Auth::check())
					<ul class="nav nav-tabs nav-stacked sb-fixed" style='width: inherit'>
					@if (!Auth::user()->is_root)
					
						<li class="dropdown">
						    <a class="dropdown-toggle" id="vendas" role="button" data-toggle="dropdown" href="#">
							    <i class='icon-list'></i> Relatórios
							    <b class="caret"></b>
						    </a>
						    <ul class="dropdown-menu" role="menu" aria-labelledby="vendas">
						    	<li>{{ HTML::linkRoute('sales.asindex','Vendas') }}</li>
						    	<li class='divider'></li>
						    	<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
						    	<li>{{ HTML::linkRoute('snapshots.index','Histórico de Lançamentos do Estoque') }}</li>
						    	<li class='divider'></li>
						    	<li>{{ HTML::linkRoute('products.index','Produtos Cadastrados') }}</li>
						    	<li>{{ HTML::linkRoute('categories.index','Categorias Cadastradas') }}</li>
						    </ul>
					    </li>

					    @if(Auth::user()->is_admin)
					    <li class="dropdown">
						    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" href="#">
							    <i class='icon-wrench'></i> Administração
							    <b class="caret"></b>
						    </a>
						    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
								<li>{{ HTML::linkRoute('products.new','Cadastrar Novo Produto') }}</li>
								<li>{{ HTML::linkRoute('categories.new','Cadastrar Nova Categoria') }}</li>
								<li class='divider'></li>
								<li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
								<li class='divider'></li>
								<li>{{ HTML::linkRoute('users.index','Usuários', array(), array('role' => 'menuitem')) }}</li>
							</ul>
					    </li>
					    @endif

					</ul>
					@else
						<li class="dropdown">
						    <a class="dropdown-toggle" id="admins" role="button" data-toggle="dropdown" href="#">
							    Super User
							    <b class="caret"></b>
						    </a>
						    <ul class="dropdown-menu" role="menu" aria-labelledby="admins">
						    	<li>{{ HTML::linkRoute('tenants.index','Tenants') }}</li>
						    	<li>{{ HTML::linkRoute('tenants.new','New Tenant') }}</li>
						    </ul>
					    </li>
					@endif
				@endif
			</div>
			
		</div>
		<div class='span9'>
			@yield('content')
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