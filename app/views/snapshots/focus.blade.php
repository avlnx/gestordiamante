@extends('layouts.default')

@section('content')
		@if ($snapshot->type == 'entry')
			<h3>Pedido de Reposição de Estoque</h3>	
			<p>Os produtos listados abaixo entraram no estoque do seu CD.</p>	
			<p class='pull-right'><em>{{ $snapshot->pretty_date }}</em></p>
		@elseif($snapshot->type == 'baixa')
			<h3>Baixa de Produtos</h3>	
			<p>Os produtos listados abaixo foram retirados do seu estoque.</p>	
			<p class='pull-right'><em>{{ $snapshot->pretty_date }}</em></p>
		@else
			<h3>Fotografia do Estoque</h3>	
			<p>Os produtos listados abaixo correspondem ao espelho do seu estoque no momento em que a fotografia foi gravada.</p>	
			<p class='pull-right'><em>{{ $snapshot->pretty_date }}</em></p>
		@endif
		<p class='lead'><small>Valor total:</small> R${{ $snapshot->total_value() }}</p>
		<p>{{ HTML::linkRoute('snapshots.delete', 'Deletar pedido', array($snapshot->id), array('class'=>'btn btn-small btn-danger'))}}</p>
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
						<td colspan='4'>Nenhum produto nessa fotografia.</td>
					</tr>
				@endif
			</tbody>

		</table>
	
@stop