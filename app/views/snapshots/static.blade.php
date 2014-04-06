@extends('layouts.default')

@section('content')

		<h2>Este é o seu estoque atual</h2>

		<p class='lead'><small>Valor total:</small> R${{ $stock->total_value() }}</p>
		<hr/>
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
				@foreach($parts as $part)
					<tr>
						<td>{{ $part->product->name }}</td>
						<td>{{ $part->quantity }}</td>
						<td>R$ {{ $part->current_price }}</td>
						<td>R$ {{ $part->quantity * $part->current_price }}</td>
					</tr>
				@endforeach
				@if(count($parts)==0)
					<tr>
						<td colspan='4'>Por enquanto não há nenhum produto no seu estoque.</td>
					</tr>
				@endif
			</tbody>

		</table>
	
@stop