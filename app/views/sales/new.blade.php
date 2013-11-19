@extends('layouts.default')

@section('content')

	

	<div class='row'>

		<div class='span6'>
			<h1>Nova venda</h1>

			{{ Form::open(array('url' => 'sales/new')) }}
			<hr/>

			<div class="input-prepend">
				<span class="add-on">Número do Pedido</span>
				<input class="span2" id="prependedInput" type="text" name="order_number">
			</div>

			@include('includes.products_list')

    	</div>

    	<div class='span3'>
    		<div class='forms-wrapper sb-fixed'>
    		
    			<h1><small>Total: </small>R$ <span id='total'>0</span></h1>
    			<hr/>

				<h4>Formas de pagamento</h4>
				{{--<p class='lead'><small>Insira em cada caixa o valor correspondente à forma de pagamento indicada.</small></p>
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
						<td><strong>Total:</strong></td>
						<td>
							<span class='pull-left'>R$&nbsp;</span> <p class='lead pull-left' id='forms-total'>0</p>
							<span class='label label-important pull-right' id='forms-label-fail' style='display:none'>0</span>
							<span class='label label-success pull-right' id='forms-label-success' style='display:none'>OK</span>
						</td>
					</tr>
				</table>
				{{ Form::submit('Gerar venda &rarr;', array('class' => 'btn btn-primary btn-large'))}}
			
	   		 	{{ Form::close() }}
	    	</div>
    	</div>
	</div>
    
@stop


@section('scripts')
	<?php @parent ?>

	$('#debit').on('change', function(){update_forms_total()});
	$('#credit').on('change', function(){update_forms_total()});
	$('#deposit').on('change', function(){update_forms_total()});
	$('#bonus').on('change', function(){update_forms_total()});
	$('#cash').on('change', function(){update_forms_total()});
	
@stop