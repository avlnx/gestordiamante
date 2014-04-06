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
					    <!--
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
						-->
						    <li class="dropdown">
							    <a class="dropdown-toggle" id="admins" role="button" data-toggle="dropdown" href="#">
								    <i class='icon-inbox'></i> Estoque
								    <b class="caret"></b>
							    </a>
							    <ul class="dropdown-menu" role="menu" aria-labelledby="admins">
							    	<li>{{ HTML::linkRoute('snapshots.stock','Visualizar Estoque Atual') }}</li>
							    	<li>{{ HTML::linkRoute('snapshots.index','Histórico de Lançamentos do Estoque') }}</li>
								    <li>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry')) }}</li>
								   	<!--
								    <li>{{ HTML::linkRoute('snapshots.new','Nova Fotografia do Estoque') }}</li>

								    <li>{{ HTML::linkRoute('snapshots.new','Nova Baixa do Estoque', array('baixa')) }}</li>
								-->

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
			