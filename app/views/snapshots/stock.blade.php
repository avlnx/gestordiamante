@extends('layouts.default')

@section('content')
	
		<div class="row">
			<div class="span8">
				<h2>Estoque Atual</h2>
				<h5>Produtos com estoque zerado não aparecem neste relatório.</h5>

				<table class='table table-condensed table-striped'>
					<tr>
						<th><p class='lead'>Estoque Real</p></th>
						<th><p class='lead'>Estoque Virtual</p></th>
						<th><a href="#" class='tooltip-trigger lead' title="Estoque disponível tanto no Estoque Real quanto no Estoque Virtual.">Estoque Disponível</a></th>
					</tr>
					<tr>
						<td><h5 class='currency'>{{ $total_stock_value }}</h5></td>
					
						<td><h5 class='currency'>{{ $total_virtual_stock }}</h5></td>
					
						<td><h2 class='currency'>{{ $total_ambos_stock }}</h2></td>
					</tr>
				</table>

			</div>
			<div class="span4">
				@if($list_option != 'todos')
					@if($list_option == 'faltando_virtual')
						<p>
							<span class="label label-warning">Visualizando produtos Faltando no Virtual</span>
						</p>
					@else
						<p>
							<span class="label label-info">Visualizando produtos Sobrando no Virtual</span>
						</p>
					@endif
				@endif

				<a href="#" class='btn pretty-button btn-info tooltip-trigger' title='Clique para mostrar ações disponíveis' id='actions-trigger'>Mostrar Ações</a>
				<div id="actions-wrapper" style='display:none'>
					<hr/>
					<p>
						{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry'), array('class' => 'btn pretty-button btn-primary')) }}
					</p>
					<p>	
						{{ HTML::linkRoute('snapshots.new','Registrar baixa', array('baixa'), array('class' => 'btn pretty-button btn-small')) }}
					</p>
					<hr/>
					<p class="lead">Filtrar resultados por produtos com diferença entre o Estoque Virtual e o Estoque Real:</p>
					<ul class="inline">
						<li>
							<p><a href="{{ URL::route('snapshots.stock')}}" class='btn btn-small pretty-button @if($list_option == "todos") active @endif'>
								Todos
							</a></p>
						</li>
						<li>
							<p><a href="{{ URL::route('snapshots.stock',['faltando_virtual'])}}" class='btn btn-small pretty-button @if($list_option == "faltando_virtual") active @endif'>
								Faltando no Estoque Virtual
							</a></p>
						</li>
						<li>
							<p><a href="{{ URL::route('snapshots.stock',['sobrando_virtual'])}}" class='btn btn-small pretty-button @if($list_option == "sobrando_virtual") active @endif'>
								Sobrando no Estoque Virtual
							</a></p>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<?php $cats = $product_list ?>

		@foreach ($cats as $category_name => $products)
			<table class='table table-hover table-condensed' style='margin: 30px 0'>
				<thead>
					<tr>
						<th>
							{{ $category_name }}
							<a href="#" class='details-trigger btn btn-mini pretty-button btn-info'>Ver Detalhes</a>
						</th>
					</tr>
					<tr>
						<th>Produto</th>
						<th>Valor unitário</th>
						<th>Valor Real</th>
						<th>Valor Virtual</th>
						<th><small>Quantidade</small> Real</th>
						<th><small>Quantidade</small> Virtual</th>
						<th>Diferença</th>
					</tr>
				</thead>
				<tbody>
					<?php $total_quantity = 0; ?>
					<?php $total_quantity_virtual = 0 ?>
					<?php $total_value = 0; ?>
					<?php $total_value_virtual = 0; ?>
					@foreach ($products as $product)
						<tr style='display:none' @if ($product->quantity_in_stock < 0 || $product->quantity_in_virtual < 0) {{ "class='error'" }}@endif>
							<td>{{ $product->name }}</td>
							
							<td class='currency'>{{ $product->price }}</td>
							<td class='currency'>{{ $product->quantity_in_stock * $product->price }}</td>
							<td class='currency'>{{ $product->quantity_in_virtual * $product->price }}</td>
							<td>
								@if($product->quantity_in_stock <= 0)
									<?php $class = 'label-important';$negative=true;?>
								@else
									<?php $class = 'label-inverse';$negative=false;?>
								@endif
								<span class='label {{$class}}'>{{ $product->quantity_in_stock }}
								@if($negative)<i class="icon-exclamation-sign icon-white"></i> @endif
								</span>
							</td>
							<td>
								@if($product->quantity_in_virtual <= 0)
									<?php $class = 'label-important';$negative=true;?>
								@else
									<?php $class = 'label-inverse';$negative=false;?>
								@endif
								<span class="label {{$class}}">
								{{ $product->quantity_in_virtual }}
								@if($negative)<i class="icon-exclamation-sign icon-white"></i> @endif
								</span>
							</td>
							<td>{{ $product->pretty_difference_in_stock }}</td>
						</tr>
						<?php $total_quantity += $product->quantity_in_stock  ?>
						<?php $total_quantity_virtual += $product->quantity_in_virtual ?>
						<?php $total_value += $product->price  * $product->quantity_in_stock ?>
						<?php $total_value_virtual += $product->price  * $product->quantity_in_virtual ?>
					@endforeach
					<tr class='totals-tr'>
						<td><h4>TOTAIS</h4></td>
						<td>&nbsp;</td>
						<td><h4 class='currency'>{{ $total_value }}</h4></td>
						<td><h4 class='currency'>{{ $total_value_virtual }}</h4></td>
						<td><h4>{{ $total_quantity }}</h4></td>
						<td><h4>{{ $total_quantity_virtual }}</h4></td>
						<td>&nbsp;</td>
						
					</tr>
				</tbody>

			</table>

			<hr/>
		@endforeach

@stop

@section('scripts')
<?php @parent ?>

$('#actions-trigger').click(function(){
	$('#actions-wrapper').toggle('slow');
	text = $(this).text();
	if(text == 'Mostrar Ações') {
		$(this).text('Ocultar Ações');
	} else {
		$(this).text('Mostrar Ações');
	}
});

$('.details-trigger').on('click', function(e){
	trs = $(this).closest('table').children('tbody').children('tr:not(.totals-tr)').toggle('slow');

	if($(this).text() == 'Ver Detalhes') {
		$(this).text('Ocultar Detalhes');
	} else {
		$(this).text('Ver Detalhes');
	}
	e.preventDefault();
});

@stop
