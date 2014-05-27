@extends('layouts.default')

@section('content')

	<?php setlocale(LC_TIME, 'pt_BR'); ?>

	<div class="row">
		<div class="span3">
			<h2>Vendas</h2>
			<p class="lead">Vendas realizadas pelo seu CD</p>
			<hr/>

			<p><a href="#" id='show_deleted'  data-toggle="button" class='btn btn-small pretty-button'>Mostrar vendas deletadas</a></p>
			<hr/>

			<p><a href="#" id='filter-date-toggle' class='btn btn-small btn-info pretty-button'>FILTRAR POR DATA</a></p>
			<div style='display: none' class='btn-group' data-toggle='buttons-radio' id='date-button-group'>
				<p><a href="#" id='latest' class='btn btn-small active pretty-button'>Vendas mais recentes</a></p>
				<hr/>
				<p>
					<!--<span class="add-on">Data Específica</span>-->
					<input class="span2" id="specific-date-input" type="text" placeholder="Ex. 14/04/2014" readonly><br/>
					<a href='#' class='btn btn-small pretty-button' id="specific-date">Data Específica</a>
				</p>
				<hr/>
				<p>
					<input class="span2" id="specific-period-input-start" type="text" placeholder="Ex. 01/04/2014" readonly><br/>
					<input class="span2" id="specific-period-input-end" type="text" placeholder="Ex. 31/04/2014" readonly><br/>
					<a href='#' class='btn btn-small pretty-button' id="specific-period">Período</a>
				</p>
				<hr/>
			</div>

			<p><a href="#" id='filter-payment-toggle' class='btn btn-small btn-info pretty-button'>FILTRAR POR FORMA DE PAGAMENTO</a></p>
			<div style='display: none' class='btn-group' data-toggle='buttons-radio' id='payment-button-group'>
				<ul class="inline">
					<li><p><a href="#" id='all_payments' class='btn active btn-small pretty-button'>Todas</a></p></li><br/>
					<li><p><a href="#" id='cash' class='btn btn-small pretty-button'>Dinheiro</a></p></li><br/>
					<li><p><a href="#" id='debit' class='btn btn-small pretty-button'>Débito</a></p></li><br/>
					<li><p><a href="#" id='credit' class='btn btn-small pretty-button'>Crédito</a></p></li><br/>
					<li><p><a href="#" id='deposit' class='btn btn-small pretty-button'>Depósito</a></p></li><br/>
					<li><p><a href="#" id='bonus' class='btn btn-small pretty-button'>Bônus e Crédito UP!</a></p></li>
				</ul>
			</div>
			<hr/>
			

		</div>

		<div class="span9">

			<div class="progress progress-striped active" style="display: none">
			  <div class="bar" style="width: 100%;">Carregando, aguarde...</div>
			</div>

			<!-- results -->
			<h4 id='pedidos-count'></h4>

			<p>
				<span class='label label-info' id='date-filter-label'>Vendas mais recentes</span>
				<span class='label label-warning' id='payment-filter-label'>Todas formas de pagamento</span>
			</p>

			<table class='table table-hover table-condensed' id='results-table'>
				
			</table>
		</div>

	</div>

@stop

@section('scripts')
	<?php @parent ?>

	function update_loader(status)
	{
		if(status == 'loading')
		{
			$('.progress').show();
		} else {
			$('.progress').hide();
		}
	}

	function get_from_server(route, payment_type)
	{
		// Disable buttons/show loader
		update_loader('loading');

		$.getJSON( route,
			
			function(data) {
			var sales = [];
			var total = 0;
			$.each(data, function(index, sale) {
				value = 0;

				if (payment_type == 'all_payments') {
					value = sale.pretty_total_value;
				} else if (payment_type == 'cash') {
					value = sale.cash;
				} else if (payment_type == 'debit') {
					value = sale.debit;
				} else if (payment_type == 'credit') {
					value = sale.credit;
				} else if (payment_type == 'deposit') {
					value = sale.deposit;
				} else if (payment_type == 'bonus') {
					value = sale.bonus;
				}

				if (sale.is_alive == 1) {total += parseFloat(value)}

				sales.push(
					"<tr><td>" + sale.pretty_order_number + 
					"</td><td><small>" + sale.meta + " há " + sale.pretty_date + " (" + sale.pretty_created_at + ")</small>" +
					"</td><td><span class='currency'>R$ " + value+
					"</span></td><td>" + sale.delete_link + 
					"</td></tr>"
				);
			});
			$('#results-table').html(sales);

			if (sales.length == 0) {
				$('#results-table').append('<tr class="info"><td colspan="4"><strong>Nenhuma venda localizada.</strong></td></tr>');
			} else {
				$('#results-table').append('<tr class="info"><td>&nbsp;</td><td>&nbsp;</td><td class="lead"><strong><span id="table-total">'+total+'</span></strong></td><td>&nbsp;</td></tr>');
				$('#table-total').currency({
					region: 	'BRL',
					thousands: '.',
					decimal: ','
				});
			}

			$('#pedidos-count').html(sales.length + ' Pedidos');


			$('#results-table tr').hover(function(event){
				$(this).find('a.delete-link').toggleClass('btn-danger disabled');
			});
			$('#results-table').find('span.deleted-item').closest('tr').hide();
		}).done(function(){
			update_loader('done');
		});

		// Enable buttons/hide loader
		//update_loader('done');
	}

	function get_active_filters(event, filter_click)
	{
		var date_done = false;
		var payment_type_done = false;

		if (filter_click == 'date') {
			date = $(event.target).attr('id');
			date_done = true;
			$('#date-filter-label').html('Vendas mais recentes');
		} else if (filter_click == 'payment_type') {
			payment_type = $(event.target).attr('id');
			payment_type_done = true;
			switch(payment_type) {
				case('all_payments'):
					friendly_type = 'Todas';
				break;
				case('credit'):
					friendly_type = 'Crédito';
				break;
				case('debit'):
					friendly_type = 'Débito';
				break;
				case('deposit'):
					friendly_type = 'Depósito e Transferência';
				break;
				case('bonus'):
					friendly_type = 'Bônus e Crédito UP!';
				break;
				case('cash'):
					friendly_type = 'Dinheiro';
				break;
			}
			$('#payment-filter-label').html(friendly_type);
		} else if (filter_click == 'specific-date') {
			date = $('#specific-date-input').val();
			date_done = true;
			$('#date-filter-label').html('Vendas em '+ $('#specific-date-input').val());
		} else if (filter_click == 'specific-period') {
			date = $('#specific-period-input-start').val() + '*' + $('#specific-period-input-end').val();
			date_done = true;
			$('#date-filter-label').html('Vendas de '+ $('#specific-period-input-start').val() + ' a ' + $('#specific-period-input-end').val());
		}

		if (!date_done) {
			date = $('#date-button-group').find('a.active').attr('id');
			if(date == 'specific-date')
			{
				date = $('#specific-date-input').val();
			} else if (date == 'specific-period') {
				date = $('#specific-period-input-start').val() + '*' + $('#specific-period-input-end').val();
			}
		}	
		if (!payment_type_done) {
			payment_type = $('#payment-button-group').find('a.active').attr('id');
		}
		console.log('payment: ' + payment_type + ' date: ' + date);

		get_from_server('sales/ajax.json/date/'+date+'/payment_type/'+payment_type, payment_type);
	}

	function toggle_deleted_items()
	{
		$('#results-table').find('span.deleted-item').closest('tr').toggle();
	}

	$('#latest,#today,#yesterday,#month,#year').bind('click', function(event){
		get_active_filters(event, 'date');
		event.preventDefault();
	});
	$('#specific-date').bind('click', function(event){
		get_active_filters(event, 'specific-date');
		event.preventDefault();
	});
	$('#specific-period').bind('click', function(event){
		get_active_filters(event, 'specific-period');
		event.preventDefault();
	});
	$('#all_payments,#cash,#debit,#credit,#deposit,#bonus').bind('click', function(event){
		get_active_filters(event, 'payment_type');
		event.preventDefault();
	});
	$('#show_deleted').bind('click', function(event){
		toggle_deleted_items();
		event.preventDefault();
	});
	
	$('#latest').trigger('click');

	$('#specific-date-input').datepicker({
		format: 'dd-mm-yyyy'
	});
	$('#specific-period-input-start').datepicker({
		format: 'dd-mm-yyyy'
	});
	$('#specific-period-input-end').datepicker({
		format: 'dd-mm-yyyy'
	});

	$('#filter-date-toggle').bind('click', function(event) {
		event.preventDefault();
		$('#date-button-group').toggle( 'slow');
	});
	$('#filter-payment-toggle').bind('click', function(event) {
		event.preventDefault();
		$('#payment-button-group').toggle('slow');
	});

@stop







