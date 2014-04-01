<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestor Diamante</title>
	<link rel="shortcut icon" href="img/favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{{ HTML::style('css/bootstrap.css') }}
	{{ HTML::style('css/bootstrap-responsive.min.css') }}

</head>
<body style='background: #fefefe'>
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

	<div class='row'>
		<div class='span12'>
			{{ HTML::image('img/logo-tiny.png', 'Gestor Diamante', array('class' => 'pull-left', 'style' => 'margin: 10px 5px 0 0'))}}
			<h4 class='pull-left'>GESTOR DIAMANTE
			<small>TOME O CONTROLE DO SEU CD</small></h4>
			<p class='pull-right' style='margin-top: 5px'>
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
			{{--<hr/>--}}
		</div>
	</div>

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
			@if (Auth::check())
				<ul class="nav nav-tabs nav-stacked sb-fixed" style='width: inherit'>
					
					<li>{{ HTML::linkRoute('home.index', 'Início') }}</li>
					<li class="dropdown">
					    <a class="dropdown-toggle" id="vendas" role="button" data-toggle="dropdown" href="#">
						    <i class='icon-shopping-cart'></i> Vendas
						    <b class="caret"></b>
					    </a>
					    <ul class="dropdown-menu" role="menu" aria-labelledby="vendas">

					    	<li>{{ HTML::linkRoute('sales.new','Nova venda') }}</li>
					    	<li>{{ HTML::linkRoute('sales.index','Últimas vendas') }}</li>
					    </ul>
				    </li>
				    <li class="dropdown">
					    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" href="#">
						    <i class='icon-tag'></i> Produtos
						    <b class="caret"></b>
					    </a>
					    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
					    	<li>{{ HTML::linkRoute('products.index','Lista de Produtos') }}</li>
					    	@if(Auth::user()->is_admin)
								<li>{{ HTML::linkRoute('products.new','Novo Produto') }}</li>
							@endif
							<li>{{ HTML::linkRoute('categories.index','Lista de Categorias') }}</li>
							@if(Auth::user()->is_admin)
								<li>{{ HTML::linkRoute('categories.new','Nova Categoria') }}</li>
							@endif
					    </ul>
				    </li>
					    @if (Auth::user()->is_admin)
						    <li class="dropdown">
							    <a class="dropdown-toggle" id="admins" role="button" data-toggle="dropdown" href="#">
								    <i class='icon-cog'></i> Administração
								    <b class="caret"></b>
							    </a>
							    <ul class="dropdown-menu" role="menu" aria-labelledby="admins">
							    	<li>{{ HTML::linkRoute('users.index','Usuários', array(), array('role' => 'menuitem')) }}</li>
							    	<li><a href="#">Configurações e Opções</a></li>
							    </ul>
						    </li>

						    <li class="dropdown">
							    <a class="dropdown-toggle" id="admins" role="button" data-toggle="dropdown" href="#">
								    <i class='icon-inbox'></i> Estoque
								    <b class="caret"></b>
							    </a>
							    <ul class="dropdown-menu" role="menu" aria-labelledby="admins">
							    	<li>{{ HTML::linkRoute('snapshots.index','Alterações no Estoque') }}</li>
								    <li>{{ HTML::linkRoute('snapshots.new','Nova Fotografia do Estoque') }}</li>
								    <li>{{ HTML::linkRoute('snapshots.new','Novo Pedido de Reposição', array('entry')) }}</li>
								    <li>{{ HTML::linkRoute('snapshots.new','Nova Baixa do Estoque', array('baixa')) }}</li>
							    </ul>
						    </li>

						    <li class="dropdown">
							    <a class="dropdown-toggle" id="admins" role="button" data-toggle="dropdown" href="#">
								    <i class='icon-signal'></i> Relatórios e Balanços
								    <b class="caret"></b>
							    </a>
							    <ul class="dropdown-menu" role="menu" aria-labelledby="admins">
							    	<li>{{ HTML::linkRoute('snapshots.index','Gerar Balanço') }}</li>
							    </ul>
						    </li>
					    @endif

					    @if (Auth::user()->is_root)
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
				</ul>
			@endif
			
			
		</div>
		<div class='span9'>
			@yield('content')
		</div>
	</div>


</div>
	{{-- HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js') --}}
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}

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