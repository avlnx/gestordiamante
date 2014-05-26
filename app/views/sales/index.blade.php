@extends('layouts.default')

@section('content')

	<?php setlocale(LC_TIME, 'pt_BR'); ?>

	<h2>Vendas <span class='label label-info'>{{ $filter_message }}</span></h2>

	<hr/>

	<p>
		Visualizar somente vendas realizadas:<br/>
		{{ HTML::linkRoute('sales.index', "Últimas Vendas" ,['latest'], array('title' => 'Visualizar últimas vendas', 'class' => 'btn btn-small')) }}
		{{ HTML::linkRoute('sales.index', "Hoje" ,['today'], array('title' => 'Visualizar vendas de hoje', 'class' => 'btn btn-small')) }}
		{{ HTML::linkRoute('sales.index', "Ontem" ,['yesterday'], array('title' => 'Visualizar vendas de ontem', 'class' => 'btn btn-small')) }}
		{{ HTML::linkRoute('sales.index', "Este Mês" ,['month'], array('title' => 'Visualizar vendas deste mês', 'class' => 'btn btn-small')) }}
		<br/>
		<p>
			Outro período: 
			{{ Form::open(array('route' => array('sales.index', 'specific_date'), 'class' => 'form-inline')) }}
				<div class="input-prepend">
					<span class="add-on">Data Inicial</span>
					{{ Form::text('start_date', null, array('placeholder' => 'Ex: 23/12/2013', 'id' => 'start_date')) }}
				</div>
				<div class="input-prepend">
					<span class="add-on">Data Final</span>
					{{ Form::text('end_date', null, array('placeholder' => 'Ex: 31/12/2013','id' => 'end_date')) }}
				</div>
			    
			    <button class='btn btn-small' type='submit'>Ir</button>
			{{ Form::close() }}
		</p>
		<hr/>
	</p>

	<table class='table table-hover table-condensed'>
		<thead>
			<tr>
				<th>Pedido</th>
				<th>Meta</th>
				<th>Valor</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; ?>
			@foreach($sales as $sale)
				<tr>
					@if (!$sale->is_alive) 
					<td style='text-decoration: line-through'>
					{{ HTML::linkRoute('sales.focus', '#'.$sale->order_number_before_delete, array($sale->id), array('class'=>'')) }}
					</td>
					@else
					<td>
					{{ HTML::linkRoute('sales.focus', '#'.$sale->order_number, array($sale->id), array('class'=>'')) }}
					</td>
					@endif
					<td>
						<!--há <em>{{ Ago::agolize($sale->created_at) }}</em> - -->
						@if (!$sale->is_alive)
							<span class="label label-important">Deletada por {{ $sale->deleted_by_user()->name }}</span><br/>
						@endif
						<small>Registrada em <em>{{ $sale->created_at->formatLocalized('%d de %B de %Y às %H:%M:%S') }}</small></em>
						<small>por {{ $sale->user()->first()->name }} </small>
					</td>
					<td @if(!$sale->is_alive) style='text-decoration: line-through' @endif >R${{ $sale->total_value() }}</td>
					<td>
						@if ($sale->is_alive && Auth::user()->is_admin)
						{{ HTML::linkRoute('sales.delete', 'Deletar', array($sale->id), array('class'=>'btn btn-mini btn-danger btn-confirm')) }}
						@endif
					</td>
				</tr>
				<?php 
				if($sale->is_alive) {
					$total += $sale->total_value(); 
				}
				?>
			@endforeach
			@if(count($sales)>0)
				<tr>
					<td>&nbsp;</td>
					<td>
						<h4>TOTAL</h4>
					</td>
					<td>
						<h4 class='currency'>{{ $total }}</h4>
					</td>
					<td>&nbsp;</td>
				</tr>
			@else
				<tr><td colspan='4'>Nenhuma venda foi encontrada.</td></tr>
			@endif
		</tbody>
	</table>
	

@stop

@section('scripts')
	<?php @parent ?>
	$('#start_date').datepicker({
		format: 'dd/mm/yyyy'
	});
	$('#end_date').datepicker({
		format: 'dd/mm/yyyy'
	});
@stop