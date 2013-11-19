@extends('layouts.default')

@section('content')

	<h2>Pedido {{ $sale->order_number }}</h2>
	<p class='pull-right'>
		<em>Realizado há {{ Ago::agolize($sale->created_at) }}</em>
	</p>
	<br class='clearfix' />

	<h4>Formas de pagamento</h4>
	<table class='table table-condensed table-hover'>
		<thead>
			<tr>
				@foreach($payments as $payment_type => $value)
					<th>{{ $payment_type }}</th>
				@endforeach
				<th><strong>Total</strong></th>
			</tr>
		</thead>
		<tbody>
			@foreach($payments as $payment_type => $value)
				<td>R$ {{ $value }}</td>
			@endforeach
			<td><p class='lead'><strong>R$ {{ $sale->total_value() }}</strong></p>
		</tbody>
	</table>

	<h4>Produtos</h4>
	<table class='table table-condensed table-hover'>
		<thead>
			<tr>
				<th>Nome</th>
				<th>Preço</th>
				<th>Quantidade</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->product->name }}</td>
				<td>R$ {{ $item->current_price }}</td>
				<td>{{ $item->quantity }}</td>
				<td>R$ {{ $item->current_price * $item->quantity }}</td>
			</tr>
		@endforeach
		@if(count($items)==0)
			<tr>
				<td colspan='4'>Nenhum produto vendido.</td>
			</tr>
		@endif
		</tbody>
	</table>

	

@stop