@extends('layouts.default')

@section('content')
	<div class='row'>

		<div class='span9'>

			@if(!isset($sale))
				{{ Form::open(array('url' => 'sales/new')) }}
			@else
				{{ Form::open(array('url' => 'sales/new/'.$sale->id)) }}
			@endif

			<h3>Informações Gerais</h3>
			@if(isset($sale))
			<div class="alert alert-info">
				<h4>Editando pedido <em>#{{$sale->order_number}}</em></h4>
				<p>Por favor, insira as informações do pedido novamente. A data não pode ser alterada.</p>
			</div>
			@endif
			<hr/>
			<div class="row">
				<div class="span4">
					<div class="input-prepend">
						<span class="add-on">Número do Pedido</span>
						<input class="span2" id="order_number" type="text" name="order_number" @if(isset($sale)) value="{{ $sale->order_number }}"@endif />
					</div>
					<p>
						<a href="#" id='add_notes'>Adicionar notas e informações ao pedido</a>
						<textarea id='notes_textarea' name="notes" rows="8" style='display:none' placeholder="Notas e informações referentes ao pedido">@if(isset($sale)){{ $sale->notes }}@endif</textarea>
					</p>
					
				</div>
				<div class="span5">
					<div id="ativacao-options" class="btn-group" data-toggle="buttons-radio">
						<p>
							<button type="button" class="btn btn-small btn active pretty-button" data-toggle="button" id="nenhuma-ativacao" style='width: inherit'>Nenhuma ativação</button>
						</p>
						<p>
							<button type="button" class="btn btn-small pretty-button" data-toggle="button" id="ativacao-up" style='width: inherit'>UP! (1)</button>
							<button type="button" class="btn btn-small pretty-button" data-toggle="button" id="ativacao-top" style='width: inherit'>Top (2)</button>
							<button type="button" class="btn btn-small pretty-button" data-toggle="button" id="ativacao-premium" style='width: inherit'>Premium (3)</button>
							<button type="button" class="btn btn-small pretty-button" data-toggle="button" id="ativacao-elite" style='width: inherit'>Elite (4)</button>
						</p>
						<p>
							<button type="button" class="btn btn-small btn-warning pretty-button" data-toggle="button" id="kit-free" style='width: inherit'>Kit Free</button>
							<input type='hidden' id='ativacoes_input' value=0 name='ativacoes_input' />
						</p>
					</div>
				</div>
			</div>
			
			

			<!--<p><button type='button' class='btn btn-small' id='test-num-perfumes'>Testar get_num_perfumes</button></p>-->

			@include('includes.products_list')

    	</div>

    	<div class='span3'>
    		<div>
    			<h1><small>Total </small><span id='total'></span><br/><span id='total-hidden' style='display:none'></span></h1>
    		</div>
    		<div class='forms-wrapper'>

    			

				<h4>Formas de pagamento</h4>
				<!--<p class="lead"><small>Utilize o formato <strong>19,40</strong> por exemplo</small></p>-->
				<div class="input-prepend">
					<span class="add-on">Dinheiro&nbsp; <strong>R$</strong></span>
					<input class="input-small reset-input payment-type float-field" onClick='this.select()' id="cash" type="text" name='cash'>
				</div>
				<div class="input-prepend">
					<span class="add-on">Débito&nbsp;&nbsp;&nbsp; <strong>R$</strong></span>
					<input class="input-small reset-input payment-type float-field" onClick='this.select()' id="debit" type="text" name='debit'>
				</div>
				<div class="input-prepend">
					<span class="add-on">Crédito&nbsp;&nbsp; <strong>R$</strong></span>
					<input class="input-small reset-input payment-type float-field" onClick='this.select()' id="credit" type="text" name='credit'>
				</div>
				
				
				<div class="input-prepend">
					<span class="add-on">Bônus&nbsp;&nbsp;&nbsp;&nbsp; <strong>R$</strong></span>
					<input class="input-small reset-input payment-type float-field" onClick='this.select()' id="bonus" type="text" name='bonus'>
				</div>
				<div class="input-prepend">
					<span class="add-on">Depósito <strong>R$</strong></span>
					<input class="input-small reset-input payment-type float-field" onClick='this.select()' id="deposit" type="text" name='deposit'>
				</div>
				<div>
					<span class='label label-important pull-right' id='forms-label-fail' style='display:none'>0</span>
					<span class='label label-success pull-right' id='forms-label-success' style='display:none'>OK</span>
					<p><strong>Total</strong></p>
					<p class='lead'>R$ <span id='forms-total'></span></p>
					
				</div>

				<p><button class='btn btn-primary btn-large pretty-button' data-loading-text='Carregando...' id='form-submit'>
					@if(!isset($sale))
						<i class='icon-white icon-ok'></i> Registrar
					@else 
						<i class='icon-white icon-edit'></i> Atualizar
					@endif
				</button></p>
				
	   		{{ Form::close() }}
	   		<p><a href='#' class='btn btn-small pretty-button' id='reset'>Reset pedido</a></p>
	    	</div>
    	</div>
	</div>

@stop


@section('scripts')
	<?php @parent ?>

	var ativacoes = 0;
	var kit_free = 0;
	var total_perfume_quantity = 0;

	// Make licenca de usos uneditable by user
	// Save id for important products
	var ativacao_id = '#qtd-unidades-'+$('#licenca-de-uso').html();
	$(ativacao_id).attr('readonly', 'readonly');
	var licenca_kit_free_id = '#qtd-unidades-'+$('#licenca-de-concessao').html();
	$(licenca_kit_free_id).attr('readonly', 'readonly');

	@if(isset($sale))
		var items = {{$sale->items}};
		// This is an edit
		// Set quantities
		$.each(items, function(i, item){
			
			id = item.product_id;
			qtd = parseInt(item.quantity);
			$('#qtd-unidades-'+id).val(qtd);
			//console.log($('#qtd-unidades-'+id).val());
			//console.log(item.product_id + ': ' + item.quantity +' '+ $('#qtd-unidades-'+item.product_id).val());
		});
		ativacoes = parseInt($(ativacao_id).val());
		kit_free = parseInt($(licenca_kit_free_id).val());
		// press button accordingly
		$('#nenhuma-ativacao,#ativacao-up,#ativacao-top,#ativacao-premium,#ativacao-elite,#kit-free').removeClass('active');
		switch(ativacoes)
		{
			case 0:
			$('#nenhuma-ativacao').addClass('active');
			break;
			case 1:
			$('#ativacao-up').addClass('active');
			break;
			case 2:
			$('#ativacao-top').addClass('active');
			break;
			case 3:
			$('#ativacao-premium').addClass('active');
			break;
			case 4:
			$('#ativacao-elite').addClass('active');
			break;
		}
		if(kit_free == 1) {
			$('#kit-free').addClass('active');
		}
		// Set formas de pagamento
		$('#cash').val(parseFloat({{$sale->cash}}));
		$('#debit').val(parseFloat({{$sale->debit}}));
		$('#deposit').val(parseFloat({{$sale->deposit}}));
		$('#credit').val(parseFloat({{$sale->credit}}));
		$('#bonus').val(parseFloat({{$sale->bonus}}));
		// update
		update_ativacoes();

	@endif

	function update_forms_total()
	{

		var debit = parseFloat($('#debit').val().replace(',','.'));
		var credit = parseFloat($('#credit').val().replace(',','.'));
		var deposit = parseFloat($('#deposit').val().replace(',','.'));
		var bonus = parseFloat($('#bonus').val().replace(',','.'));
		var cash = parseFloat($('#cash').val().replace(',','.'));

		var total = (debit + credit + deposit + bonus + cash).toFixed(2);

		//alert(total);

		$('#forms-total').html(total);

		var total_geral = parseFloat($('#total-hidden').html()).toFixed(2);
		var forms_total = total;

		if (total_geral != forms_total)
		{
			var difference = (forms_total-total_geral).toFixed(2);
			$('#forms-label-success').hide();
			$('#forms-label-fail').show();
			$('#forms-label-fail').html(difference);

		}
		else
		{
			$('#forms-label-fail').hide();
			$('#forms-label-success').show();
		}
	}

	function update_ativacoes()
	{
		// Estamos usando o slug do produto licenca de uso para
		// pegar o id do produto licenca de uso e utiliza-lo para atualizar
		// as ativacoes
		$(ativacao_id).val(ativacoes);
		$('#ativacoes_input').val(ativacoes);
		// update kit free
		$(licenca_kit_free_id).val(kit_free);
		//console.log(licenca_kit_free_id);
		global_update_qtd_badge();
		run_calculations();
		update_forms_total();
	}

	function update_num_perfumes()
	{
		total_perfume_quantity = 0;
		var perfumes = $('.perfumes-masculinos, .perfumes-femininos,.perfumes-unisex');
		//console.log(perfumes);

		$.each(perfumes, function(index, perfume){
			total_perfume_quantity += parseFloat($(perfume).val());
		});
		console.log(total_perfume_quantity);
		//console.log(perfumes);

	}

	function validate_ativacoes()
	{
		update_num_perfumes();
		result = false;
		if (ativacoes == 0 || total_perfume_quantity >= ativacoes) {
			result = true;
		}
		return result;
	}

	$('#debit').on('change', function(){update_forms_total()});
	$('#credit').on('change', function(){update_forms_total()});
	$('#deposit').on('change', function(){update_forms_total()});
	$('#bonus').on('change', function(){update_forms_total()});
	$('#cash').on('change', function(){update_forms_total()});

	function clean_money() {
		var debit = parseFloat($('#debit').val().replace(',','.'));
		$('#debit').val(debit);
		var credit = parseFloat($('#credit').val().replace(',','.'));
		$('#credit').val(credit);
		var deposit = parseFloat($('#deposit').val().replace(',','.'));
		$('#deposit').val(deposit);
		var bonus = parseFloat($('#bonus').val().replace(',','.'));
		$('#bonus').val(bonus);
		var cash = parseFloat($('#cash').val().replace(',','.'));
		$('#cash').val(cash);
	}

	$('form').on('submit', function(event){
		
		if(!$('#order_number').val())
		{
			// Número do pedido está vazio
			if(confirm('Este pedido será gravado sem um número de pedido. Confirma?'))
			{
				console.log('Pedido sem número gravado.');
				$('#form-submit-btn').button('loading');
			} else {
				event.preventDefault();
			}
		}

		if(!validate_ativacoes()) {
			//alert('Por favor, adicione ' + (ativacoes - total_perfume_quantity) + ' perfume(s) ao pedido para se igualar ao número de ativações.');
			// Pedido de ativações sem Perfumes
			if(confirm('O número de perfumes não bate com o número de ativações. Confirma?'))
			{
				console.log('Pedido sem número gravado.');
				$('#form-submit-btn').button('loading');
			} else {
				event.preventDefault();
			}
		}

		clean_money();
		
	});

	$('#add_notes').click(function(event){
		//console.log('add_notes clicked!');
		$('#notes_textarea').toggle('slow');
		event.preventDefault();
	});

	$('#nenhuma-ativacao').click(function(event){
		console.log('Sem ativação');
		ativacoes = 0;
		kit_free = 0;
		update_ativacoes();
	});
	$('#ativacao-up').click(function(event){
		console.log('Ativação UP!');
		ativacoes = 1;
		kit_free = 0;
		update_ativacoes();
	});
	$('#ativacao-top').click(function(event){
		console.log('Ativação Top');
		ativacoes = 2;
		kit_free = 0;
		update_ativacoes();
	});
	$('#ativacao-premium').click(function(event){
		console.log('Ativação Premium');
		ativacoes = 3;
		kit_free = 0;
		update_ativacoes();
	});
	$('#ativacao-elite').click(function(event){
		console.log('Ativação Elite');
		ativacoes = 4;
		kit_free = 0;
		update_ativacoes();
	});
	$('#kit-free').click(function(event){
		console.log('Kit free');
		ativacoes = 0;
		kit_free = 1;
		update_ativacoes();
	});
	$('#reset').click(function(event){
		$('.qtdd').val(0);
		$('.payment-type').val(0);

		global_update_qtd_badge();
		run_calculations();
		update_forms_total();
		console.log('reset!');
		event.preventDefault();
	});
@stop
