@extends('layouts.default')

@section('content')

	<h2>Alterações do Estoque</h2>
	<p>
		
		{{ HTML::linkRoute('snapshots.new', "Novo Pedido de Reposição" ,'entry', array('title' => 'Criar novo pedido de reposição do estoque do CD', 'class' => 'btn btn-primary')) }}
		
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

				Fotografia tirada há <em>{{ Ago::agolize($snapshot->created_at) }}</em>
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
				{{ HTML::linkRoute('snapshots.focus', 'Visualizar detalhes', array($snapshot->id), array('class'=>'btn btn-small'))}}
			</td>
		</tr>


	@endforeach
	@if(count($snapshots)==0)
		<tr><td colspan='4'>Nenhuma fotografia por enquanto.</td></tr>
	@endif
		</tbody>
	</table>

	

@stop