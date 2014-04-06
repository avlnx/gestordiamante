@extends('layouts.default')

@section('content')

		<h2 class='text-right'>Este é o seu estoque atual
		<small>{{ $total_stock or 0 }}</small></h2>
		<hr>
		<?php print_r($product_list) ?>

		@foreach ($categories as $category)
			<p class='lead'>{{ $category->name }}</p>

			<table class='table table-hover table-condensed table-striped table-bordered'>
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
					@foreach ($category->products as $product)

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
						<td><strong>TOTAIS</strong></td>
						<td>{{ $total_quantity }}</td>
						<td>---</td>
						<td>{{ $total_value }}</td>
					</tr>
				</tbody>

			</table>

			<hr>

		@endforeach

		<hr/>
		
	
@stop