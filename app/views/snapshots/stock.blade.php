@extends('layouts.default')

@section('content')

		<h2>Este é o seu estoque atual</h2>

		@foreach ($categories as $category)
			<p class='lead'>{{ $category->name }}</p>

			<table class='table table-hover table-condensed'>
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

						<tr>
							<td>{{ $product->name }}</td>
							<td>{{ $product->quantity_in_stock }}</td>
							<td>R$ {{ $product->price }}</td>
							<td>R$ {{ $product->quantity_in_stock * $product->price }}</td>
						</tr>
						<?php $total_quantity += $product->quantity_in_stock  ?> 
						<?php $total_value += $product->price  * $product->quantity_in_stock ?>

					@endforeach
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>{{ $total_quantity }}</td>
						<td>{{ $total_value }}</td>
					</tr>
				</tbody>

			</table>

			<hr>

		@endforeach

		<hr/>
		
	
@stop