@extends('layouts.default')

@section('content')

	<?php setlocale(LC_TIME, 'pt_BR'); ?>

	<div class="row">
		<div class="span3">
			<h2>Vendas</h2>
			<p class="lead">Vendas realizadas pelo seu CD</p>
			<hr/>
			<h4>Filtros</h4>

			<p>
				<i class='icon icon-calendar'></i>
				<a href="#" id='filter-date-toggle'>Data</a>
			</p>
			<div id='date-button-group'>
				<p>
					<span class='label' id='specific-date-label'>Data específica</span>
					<div class='input-prepend'>
						<span class="add-on">Dia</span>
						<input class="input-small" id="specific-date-input" type="text" readonly>
					</div>
					<!--<a href='#' class='btn btn-small pretty-button' id="specific-date">Data Específica</a>-->
				</p>
				
				<p>
					<span class='label' id='specific-period-label'>Período</span>
					<div class='input-prepend'>
						<span class="add-on">De</span>
						<input class="input-small" id="specific-period-input-start" type="text" readonly>
					</div><br/>
					<div class='input-prepend'>
						<span class="add-on">Até</span>
						<input class="input-small" id="specific-period-input-end" type="text" readonly>
					</div>
					
					<!--<a href='#' class='btn btn-small pretty-button' id="specific-period">Período</a>-->
				</p>
			</div>

			<hr/>

			<p>
				<i class='icon icon-briefcase'></i>
				<a href="#" id='filter-payment-toggle'>Formas de Pagamento</a>
			</p>

			<div class='btn-group' data-toggle='buttons-radio' id='payment-button-group'>
				<ul class="inline">
					<li><p><a href="#" id='Todas' class='btn btn-warning active btn-mini pretty-button'>Todas</a></p></li><br/>
					<li><p><a href="#" id='Dinheiro' class='btn btn-mini pretty-button'>Dinheiro</a></p></li><br/>
					<li><p><a href="#" id='Debito' class='btn btn-mini pretty-button'>Débito</a></p></li><br/>
					<li><p><a href="#" id='Credito' class='btn btn-mini pretty-button'>Crédito</a></p></li><br/>
					<li><p><a href="#" id='Deposito' class='btn btn-mini pretty-button'>Depósito</a></p></li><br/>
					<li><p><a href="#" id='Bonus' class='btn btn-mini pretty-button'>Bônus e Crédito UP!</a></p></li>
				</ul>
			</div>
			<hr/>
			<p>
				<i class='icon icon-trash'></i>
				<a href="#" id='show_deleted'  data-toggle="button" class='btn-ssmall'>Mostrar vendas deletadas</a>
			</p>

		</div>

		<div class="span9">

			<div class="progress progress-striped active" style="display: none">
			  <div class="bar" style="width: 100%;">Carregando, aguarde...</div>
			</div>

			<!-- results -->
			<h4 id='pedidos-count'></h4>

			<p>
				<i class='icon icon-calendar'></i> <span class='label label-warning' id='date-filter-label'>Vendas mais recentes</span>
			</p>
			<p>
				<i class='icon icon-briefcase'></i> <span class='label label-warning' id='payment-filter-label'>Todos</span>
			</p>

			<table class='table table-hover table-condensed' id='results-table'>
			</table>
		</div>

	</div>

@stop

@section('scripts')
	<?php @parent ?>

	var deleted_sales_visible = false;
	var payment_type = 'Todas';
	// clear date inputs
	$('#specific-date-input').val("");
	$('#specific-period-input-end').val("");
	$('#specific-period-input-start').val("");

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
			var sales_count = 0;
			$.each(data, function(index, sale) {
				value = 0;

				if (payment_type == 'Todas') {
					value = sale.pretty_total_value;
				} else if (payment_type == 'Dinheiro') {
					value = sale.cash;
				} else if (payment_type == 'Debito') {
					value = sale.debit;
				} else if (payment_type == 'Credito') {
					value = sale.credit;
				} else if (payment_type == 'Deposito') {
					value = sale.deposit;
				} else if (payment_type == 'Bonus') {
					value = sale.bonus;
				}

				if (sale.is_alive == 1) {
					total += parseFloat(value);
					sales_count++;
				}

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

			$('#pedidos-count').html(sales_count + ' Pedidos');


			$('#results-table tr').hover(function(event){
				$(this).find('a.delete-link').toggleClass('btn-danger disabled');
			});
			$('#results-table').find('span.deleted-item').closest('tr').hide();
		}).done(function(){
			update_loader('done');
		});
	}

	function get_active_filters()
	{
		var date = 'latest';
		//var payment_type = 'Todos';

		// Get inputs
		var s_date = $('#specific-date-input');
		var s_date_start = $('#specific-period-input-start');
		var s_date_end = $('#specific-period-input-end');

		// Get labels
		var date_label = $('#date-filter-label');
		var payment_label = $('#payment-filter-label');

		// get 'date'
		if(s_date.val() != '') {
			// specific date
			date_label.html('Vendas em ' + s_date.val());
			date = $('#specific-date-input').val().replace(/[/]/g,'-');	// clean / to -
			$('#specific-date-label').addClass('label-warning');
			$('#specific-period-label').removeClass('label-warning');
		} else if(s_date_start.val() != '') {
			// specific-period
			date_label.html('Vendas de ' + s_date_start.val() + " a " + s_date_end.val());
			date = s_date_start.val().replace(/[/]/g,'-') + '*' + s_date_end.val().replace(/[/]/g,'-');	
			$('#specific-date-label').removeClass('label-warning');
			$('#specific-period-label').addClass('label-warning');	
		} else {
			// latest
			date_label.html('Vendas mais recentes');
			date = 'latest';
			$('#specific-date-label').removeClass('label-warning');
			$('#specific-period-label').removeClass('label-warning');	
		}


		// Get 'payment_type'
		// get active button
		//payment_type = $('#payment-button-group').find('a.active').attr('id');
		console.log(payment_type);
		payment_label.html(payment_type);

		// Call ajax
		console.log('payment: ' + payment_type + ' date: ' + date);

		get_from_server('sales/ajax.json/date/'+date+'/payment_type/'+payment_type, payment_type);
	}

	function toggle_deleted_items()
	{
		var show_deleted = $('#show_deleted');
		if(deleted_sales_visible == false) {
			// show deleted sales
			$('#results-table').find('span.deleted-item').closest('tr').show();
			$(show_deleted).text('Esconder vendas deletadas');
			deleted_sales_visible = true;
		} else {
			// hide deleted sales
			$('#results-table').find('span.deleted-item').closest('tr').hide();
			$(show_deleted).text('Mostrar vendas deletadas');
			deleted_sales_visible = false;
		}
	}

	$('#Todas,#Dinheiro,#Debito,#Credito,#Deposito,#Bonus').bind('click', function(event){
		payment_type = $(this).attr('id');
		get_active_filters();
		event.preventDefault();
	});

	$('#show_deleted').bind('click', function(event){
		toggle_deleted_items();
		event.preventDefault();
	});

	var sp_date_input = $('#specific-date-input').datepicker({
		format: 'dd/mm/yyyy',
	}).on('changeDate', function(ev){
		sp_date_input.hide();
		$('#specific-period-input-start,#specific-period-input-end').val("");
		//clear_specific_period_dates_input();
		get_active_filters();
	}).data('datepicker');

	var sp_period_start = $('#specific-period-input-start').datepicker({
		format: 'dd/mm/yyyy'
	}).on('changeDate', function(ev){
		$('#specific-date-input').val("");
		
		if (ev.date.valueOf() > sp_period_end.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			sp_period_end.setValue(newDate);
		}
		
		sp_period_start.hide();
		$('#specific-period-input-end')[0].focus();
	}).data('datepicker');

	var sp_period_end = $('#specific-period-input-end').datepicker({
		format: 'dd/mm/yyyy',
		/*
		onRender: function(date) {
			return date.valueOf() <= sp_period_start.date.valueOf() ? 'disabled' : '';
		}
		*/
	}).on('changeDate', function(ev){
		sp_period_end.hide();
		if (ev.date.valueOf() < sp_period_start.date.valueOf()) {
			var newDate = new Date(sp_period_start.date)
			newDate.setDate(newDate.getDate() + 1);
			sp_period_end.setValue(newDate);
		}
		get_active_filters();
	}).data('datepicker');

	$('#filter-date-toggle').bind('click', function(event) {
		event.preventDefault();
		$('#date-button-group').toggle( 'slow');
	});
	$('#filter-payment-toggle').bind('click', function(event) {
		event.preventDefault();
		$('#payment-button-group').toggle('slow');
	});

	get_active_filters();
@stop







