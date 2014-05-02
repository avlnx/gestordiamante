@extends('layouts.default')

@section('content')

	<h2>Histórico de Alterações do Estoque</h2>
	<p>
		{{ HTML::linkRoute('snapshots.new', "Lançar Novo Pedido de Reposição" ,'entry', array('title' => 'Criar novo pedido de reposição do estoque do CD', 'class' => 'btn btn-primary')) }}
	</p>

	<table class='table table-hover table-condensed'>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>Total</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
	@foreach($snapshots as $snapshot)
		<tr>
			<td>

				Lançamento realizado há <em>{{ $snapshot->pretty_date }}</em><br/>
				<small>por {{ $snapshot->creator }} </small>
			</td>
			<td>
				@if($snapshot->type == 'entry')
					<span class='label label-success'>Pedido de Reposição</span>
				@elseif($snapshot->type == 'baixa')
					<span class='label label-important'>Baixa de Estoque</span>
				@else
					<span class='label'>Contagem do Estoque</span>
				@endif
			</td>
			<td>
				<p class='lead'>R$ {{ $snapshot->total_value() }}</p>
			</td>
			<td>
				{{ HTML::linkRoute('snapshots.focus', 'Visualizar detalhes', array($snapshot->id), array('class'=>'btn btn-mini'))}}
				{{ HTML::linkRoute('snapshots.delete', 'Deletar pedido', array($snapshot->id), array('class'=>'btn btn-mini btn-danger'))}}
			</td>
		</tr>


	@endforeach
	@if(count($snapshots)==0)
		<tr><td colspan='4'>Nenhum lançamento por enquanto.</td></tr>
	@endif
		</tbody>
	</table>

	

@stop
