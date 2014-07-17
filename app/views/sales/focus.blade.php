@extends('layouts.default')

@section('content')

	@include('includes.back-button', array('route' => URL::route('sales.asindex')))

	<h2>Pedido #{{ $sale->order_number }}</h2>
	<ul class="inline">
		<li>
			<a href='{{route('sales.delete', $sale->id)}}' class='btn confirm btn-danger pretty-button'><i class='icon icon-white icon-trash'></i> Deletar</a>
			<a href='{{route('sales.edit', $sale->id)}}' class='btn pretty-button'><i class='icon icon-edit'></i> Editar</a>
		</li>
	</ul>

	<p class='pull-right'>
		<em>Realizado há {{ $sale->pretty_date }} por {{ $sale->creator }}</em>
	</p>
	<br class='clearfix' />

	<table class='table table-condensed table-hover'>
		<thead>
			<tr>
				<th><h6>Formas de Pagamento</h6></th>
			</tr>
			<tr>
				@foreach($payments as $payment_type => $value)
					<th>{{ $payment_type }}</th>
				@endforeach
				<th><strong>Total</strong></th>
			</tr>
		</thead>
		<tbody>
			@foreach($payments as $payment_type => $value)
				<td class='currency'>{{ $value }}</td>
			@endforeach
			<td><p class='lead'><strong class='currency'>{{ $sale->total_value() }}</strong></p>
		</tbody>
	</table>

	<table class='table table-condensed table-hover'>
		<thead>
			<tr>
			<th><h6>Produtos</h6></th>
			</tr>
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
				<td class='currency'>{{ $item->current_price }}</td>
				<td><span class='label label-info'>{{ $item->quantity }}</span></td>
				<td class='currency'>{{ $item->current_price * $item->quantity }}</td>
			</tr>
		@endforeach
		@if(count($items)==0)
			<tr>
				<td colspan='4'>Nenhum produto vendido.</td>
			</tr>
		@endif
		</tbody>
	</table>

	@if ($sale->notes)
		<blockquote>
			<p class='lead'>Notas</p>
			<p>{{$sale->notes}}</p>
		</blockquote>
	@endif

@stop