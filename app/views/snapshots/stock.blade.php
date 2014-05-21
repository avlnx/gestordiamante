@extends('layouts.default')

@section('content')
		<div class="row">
			<div class="span6">
				<h2 class='text-left'>Este é o seu estoque atual</h2>
				<h5 class='text-left'>Produtos com estoque zerado não aparecem neste relatório.</h5>
				<p>{{ HTML::linkRoute('snapshots.new','Lançar Pedido de Reposição', array('entry'), array('class' => 'btn btn-primary')) }}</p>
			</div>
			<div class="span3">
				<h2 class="text-left">R$ {{ $total_stock_value }}</h2>
			</div>
		</div>


		<hr>

		<?php $cats = $product_list_in_stock ?>

		@foreach ($cats as $category_name => $products)
			<p class="lead">{{ $category_name }}</p>

			<table class='table table-hover table-condensed table-striped'>
				<thead>
					<tr>
						<th>Produto</th>
						<th>Quantidade</th>
						<th>Valor unitário</th>
						<th>Valor total</th>
					</tr>
				</thead>
				<tbody>
					<?php $total_quantity = 0; ?>
					<?php $total_value = 0; ?>
					@foreach ($products as $product)
						<tr @if ($product->quantity_in_stock <= 0) {{ "class='error'" }}@endif>
							<td>{{ $product->name }}</td>
							<td>{{ $product->quantity_in_stock }}</td>
							<td>R$ {{ $product->price }}</td>
							<td>R$ {{ $product->quantity_in_stock * $product->price }}</td>
						</tr>
						<?php $total_quantity += $product->quantity_in_stock  ?>
						<?php $total_value += $product->price  * $product->quantity_in_stock ?>
					@endforeach
					<tr>
						<td><h4>TOTAIS</h4></td>
						<td><h4>{{ $total_quantity }}</h4></td>
						<td>---</td>
						<td><h4>R$ {{ $total_value }}</h4></td>
					</tr>
				</tbody>

			</table>
		@endforeach

@stop
