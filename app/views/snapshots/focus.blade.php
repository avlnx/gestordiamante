@extends('layouts.default')


@section('content')

		@include('includes.back-button', array('route' => URL::route('snapshots.index')))

		@if ($snapshot->type == 'entry')
			<h3>Pedido de Reposição de Estoque </h3>	
			<p>Os produtos listados abaixo entraram no estoque {{ $snapshot->virtual_real_or_ambos}} do seu CD.</p>	
			<p class='pull-right'><em>{{ $snapshot->pretty_date }} por {{$snapshot->creator}}</em></p>
		@elseif($snapshot->type == 'baixa')
			<h3>Baixa de Produtos </h3>	
			<p>Os produtos listados abaixo foram retirados do estoque {{ $snapshot->virtual_real_or_ambos}} do seu CD.</p>	
			<p class='pull-right'><em>{{ $snapshot->pretty_date }} por {{$snapshot->creator}}</em></p>
		@endif
		<p class='lead'><small>Valor total:</small> <span class='currency'>{{ $snapshot->total_value() }}</span></p>
		<p>{{ HTML::linkRoute('snapshots.delete', 'Deletar pedido', array($snapshot->id), array('class'=>'btn btn-small btn-danger pretty-button'))}}</p>
		<hr/>
		<table class='table table-hover table-condensed'>
			<thead>
				<tr>
					<th>Produto</th>
					<th>Quantidade Real</th>
					<th>Quantidade Virtual</th>
					<th>Valor unitário</th>
					<th>Valor total</th>
				</tr>
			</thead>
			<tbody>
				<?php $quantity = 0; ?>
				<?php $virtual_quantity = 0; ?>
				@foreach($parts as $part)
					<tr>
						<td>{{ $part->product->name }}</td>
						<td>
							{{ $part->quantity }}</td>
						<td>
							{{ $part->virtual_quantity }}
						</td>
						<td class='currency'>{{ $part->current_price }}</td>
						<td class='currency'>{{ $part->quantity * $part->current_price }}</td>
					</tr>
					<?php $quantity += $part->quantity; ?>
					<?php $virtual_quantity += $part->virtual_quantity; ?>
				@endforeach
				@if(count($parts)==0)
					<tr>
						<td colspan='4'>Nenhum produto nessa fotografia.</td>
					</tr>
				@else
					<tr>
						<td><small><strong>TOTAL</strong></small></td>
						<td>{{ $quantity }}</td>
						<td>{{ $virtual_quantity }}</td>
						<td>&nbsp;</td>
						<td class='currency'>{{ $snapshot->total_value() }}</td>
					</tr>
				@endif
			</tbody>

		</table>
	
@stop