@extends('layouts.default')

@section('content')

	<?php setlocale(LC_TIME, 'pt_BR'); ?>

	<h2>Vendas</h2>

	<h6>FILTRAR POR DATA</h6>
	<div class='btn-group' data-toggle='buttons-radio' id='date-button-group'>
		<a href="#" id='latest' class='btn active'>Últimas 200 vendas</a>
		<a href="#" id='today' class='btn'>Hoje</a>
		<a href="#" id='yesterday' class='btn'>Ontem</a>
		<a href="#" id='month' class='btn'>Este mês</a>
		<a href="#" id='year' class='btn'>Este ano</a>
	</div>

	<h6>FILTRAR POR FORMA DE PAGAMENTO</h6>
	<div class='btn-group' data-toggle='buttons-radio' id='payment-button-group'>
		<a href="#" id='all_payments' class='btn active'>Todas</a>
		<a href="#" id='cash' class='btn'>Dinheiro</a>
		<a href="#" id='debit' class='btn'>Débito</a>
		<a href="#" id='credit' class='btn'>Crédito</a>
		<a href="#" id='deposit' class='btn'>Depósito</a>
		<a href="#" id='bonus' class='btn'>Bônus e Transferência de Crédito UP!</a>
	</div>
	<p></p>

	<!-- results -->
	<table class='table table-hover table-condensed' id='results-table'>
		
	</table>
	<style>
		.deleted {text-decoration: line-through;}
	</style>
@stop

@section('scripts')
	<?php @parent ?>

	function get_from_server(route)
	{
		$.getJSON( route,
			function(data) {
			//console.log(data);	
			var sales = [];
			var total = 0;
			$.each(data, function(index, sale) {
				//console.log(sale.order_number);
				if (sale.is_alive == 1) {total += sale.total_value}
				sales.push(
					"<tr><td>" + sale.pretty_order_number + 
					"</td><td><small>" + sale.meta + " há " + sale.pretty_date + "</small>" +
					"</td><td>R$ " + sale.pretty_total_value+
					"</td><td>" + sale.delete_link + 
					"</td></tr>"
				);
			});
			$('#results-table').html(sales);

			if (sales.length == 0) {
				$('#results-table').append('<tr class="info"><td colspan="4"><strong>Nenhuma venda localizada.</strong></td></tr>');
			} else {
				$('#results-table').append('<tr class="info"><td>&nbsp;</td><td>&nbsp;</td><td><strong>R$ '+total+'</strong></td><td>&nbsp;</td></tr>');
			}

			
			//console.log(sales);
			$('#results-table tr').hover(function(event){
				//console.log($(this).find('a.delete-link'));
				//console.log(event.target.closest('a'));
				$(this).find('a.delete-link').toggleClass('btn-danger disabled');
			});
		});
	}

	function get_active_filters(event, filter_click)
	{
		var date_done = false;
		var payment_type_done = false;

		if (filter_click == 'date') {
			date = $(event.target).attr('id');
			date_done = true;
		} else if (filter_click == 'payment_type') {
			payment_type = $(event.target).attr('id');
			payment_type_done = true;
		}

		if (!date_done) {
			date = $('#date-button-group').find('a.active').attr('id');
		}	
		if (!payment_type_done) {
			payment_type = $('#payment-button-group').find('a.active').attr('id');
		}

		console.log('payment: ' + payment_type + ' date: ' + date);

		//date = $('#date-button-group').find('a.active');

		get_from_server('sales/ajax.json/date/'+date+'/payment_type/'+payment_type);
	}

	$('#latest,#today,#yesterday,#month,#year').bind('click', function(event){
		get_active_filters(event, 'date');
		event.preventDefault();
	});
	$('#all_payments,#cash,#debit,#credit,#deposit,#bonus').bind('click', function(event){
		get_active_filters(event, 'payment_type');
		event.preventDefault();
	});
	
	$('#latest').trigger('click');

@stop







