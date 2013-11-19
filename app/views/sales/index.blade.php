@extends('layouts.default')

@section('content')

	<h2>Últimas Vendas</h2>
	{{-- HTML::link_to_route('sales.new', "Nova Venda" ,'', array('title' => 'Criar nova venda', 'class' => 'btn btn-large btn-primary')) --}}


	<table class='table table-hover table-condensed'>
		<thead>
			<tr>
				<th>Pedido</th>
				<th>Quando</th>
				<th>Valor</th>
			</tr>
		</thead>
		<tbody>
	@foreach($sales as $sale)
		<tr>
			<td>{{ HTML::link_to_route('sales.focus', '#'.$sale->order_number, array($sale->id), array('class'=>'')) }}</td>
			<td>há <em>{{ Ago::agolize($sale->created_at) }}</em></td>
			<td><strong>R${{ $sale->total_value() }}</strong></td>
		</tr>
	@endforeach
	@if(count($sales)==0)
		<tr><td colspan='4'>Nenhuma venda por enquanto.</td></tr>
	@endif
		</tbody>
	</table>
	

@stop