@extends('layouts.default')

@section('content')
	<div class='row'>

		<div class='span9'>

			{{ Form::open(array('url' => 'sales/new')) }}


			<div class="input-prepend">
				<span class="add-on">Número do Pedido</span>
				<input class="span2" id="order_number" type="text" name="order_number">
			</div>

			<br>
			<a href="#" id='add_notes'>Adicionar notas e informações ao pedido</a><br/>
			<textarea id='notes_textarea' name="notes" rows="8" style='display:none' placeholder="Notas e informações referentes ao pedido"></textarea>
			<br/>

			<h5>Ativação</h5>
			<div id="ativacao-options" class="btn-group" data-toggle="buttons-radio">
				<button type="button" class="btn btn-small btn active" data-toggle="button" id="nenhuma-ativacao" style='width: inherit'>Nenhuma ativação</button>
				<button type="button" class="btn btn-small btn-info" data-toggle="button" id="ativacao-up" style='width: inherit'>UP! (1)</button>
				<button type="button" class="btn btn-small btn-info" data-toggle="button" id="ativacao-top" style='width: inherit'>Top (2)</button>
				<button type="button" class="btn btn-small btn-info" data-toggle="button" id="ativacao-premium" style='width: inherit'>Premium (3)</button>
				<button type="button" class="btn btn-small btn-info" data-toggle="button" id="ativacao-elite" style='width: inherit'>Elite (4)</button>
				<button type="button" class="btn btn-small btn-warning" data-toggle="button" id="kit-free" style='width: inherit'>Kit Free</button>
				<input type='hidden' id='ativacoes_input' value=0 name='ativacoes_input' />
			</div>

			<!--<p><button type='button' class='btn btn-small' id='test-num-perfumes'>Testar get_num_perfumes</button></p>-->

			@include('includes.products_list')

    	</div>

    	<div class='span3'>
    		<div class='forms-wrapper sb-fixed'>

    			<h1><small>Total </small><span id='total'></span><br/><span id='total-hidden' style='display:none'></span></h1>
    			<hr/>

				<h4>Formas de pagamento</h4>
				{{--<p class='lead'><small>Insira em cada caixa o valor correspondente à forma de pagamento indicada.</small></p>--}}
				<table class='table table-condensed table-hover'>
					<tr>
						<td>Débito</td>
						<td>
							<div class="input-prepend">
								<span class="add-on">R$</span>
								<input class="input-small reset-input" onClick='this.select()' id="debit" type="text" name='debit'>
							</div>

						</td>
					</tr>
					<tr>
						<td>Crédito</td>
						<td>
							<div class="input-prepend">
								<span class="add-on">R$</span>
								<input class="input-small reset-input" onClick='this.select()' id="credit" type="text" name='credit'>
							</div>
						</td>
					</tr>
					<tr>
						<td>Dinheiro</td>
						<td>
							<div class="input-prepend">
								<span class="add-on">R$</span>
								<input class="input-small reset-input" onClick='this.select()' id="cash" type="text" name='cash'>
							</div>
						</td>
					</tr>
					<tr>
						<td>Depósito ou Transferência</td>
						<td>
							<div class="input-prepend">
								<span class="add-on">R$</span>
								<input class="input-small reset-input" onClick='this.select()' id="deposit" type="text" name='deposit'>
							</div>
						</td>
					</tr>
					<tr>
						<td>Bônus</td>
						<td>
							<div class="input-prepend">
								<span class="add-on">R$</span>
								<input class="input-small reset-input" onClick='this.select()' id="bonus" type="text" name='bonus'>
							</div>
						</td>
					</tr>
					<tr>
						<td><strong>Total</strong></td>
						<td>
							<p class='lead pull-left' id='forms-total'></p>
							<span class='label label-important pull-right' id='forms-label-fail' style='display:none'>0</span>
							<span class='label label-success pull-right' id='forms-label-success' style='display:none'>OK</span>
						</td>
					</tr>
				</table>
				{{ Form::submit('Registrar venda &rarr;', array('class' => 'btn btn-primary btn-large'))}}

	   		 	{{ Form::close() }}
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

	function update_ativacoes()
	{
		// Estamos usando o slug do produto licenca de uso para
		// pegar o id do produto licenca de uso e utiliza-lo para atualizar
		// as ativacoes
		$(ativacao_id).val(ativacoes);
		$('#ativacoes_input').val(ativacoes);
		// update kit free
		$(licenca_kit_free_id).val(kit_free);
		console.log(licenca_kit_free_id);
		run_calculations();
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
		if (ativacoes == 0)
		{
			result = true;
		} else if (total_perfume_quantity >= ativacoes) {
			result = true;
		}
		return result;
	}

	$('#debit').on('change', function(){update_forms_total()});
	$('#credit').on('change', function(){update_forms_total()});
	$('#deposit').on('change', function(){update_forms_total()});
	$('#bonus').on('change', function(){update_forms_total()});
	$('#cash').on('change', function(){update_forms_total()});

	$('form').on('submit', function(event){
		
		if(!$('#order_number').val())
		{
			// Número do pedido está vazio
			if(confirm('Este pedido será gravado sem um número de pedido. Confirma?'))
			{
				console.log('Pedido sem número gravado.');
			} else {
				event.preventDefault();
			}
		}

		if(!validate_ativacoes()) {
			alert('Por favor, adicione ' + (ativacoes - total_perfume_quantity) + ' perfume(s) ao pedido para se igualar ao número de ativações.');
			//alert('Por favor, adicione ao menos ' + ativacoes + ' perfumes ao pedido para se igualar ao número de ativações.');
			event.preventDefault();
		}
		
	});

	$('#add_notes').click(function(event){
		//console.log('add_notes clicked!');
		$('#notes_textarea').toggle('slow');
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
	/*
	$('#test-num-perfumes').click(function(event){
		event.preventDefault();
		get_num_perfumes();
	});

	get_num_perfumes();
	*/
@stop
