 		<h3>Produtos</h3>

		<?php //print_r($products->toJson()) ?>

			<?php $found = False ?>
			@foreach($categories as $category)
				<a class='no-format' href='#' id='table-toggler-{{ $category->id }}'><p class='lead'><i class='icon-chevron-right'></i>  {{ $category->name }}</p></a>
				<div id='table-wrapper-{{ $category->id }}' style='display: none'>
					<ul class="inline">

					@foreach($products as $product)
						@if($product->category == $category)
							<?php $found = True ?>
							<li id='prod-row-{{$product->id}}' style='border: 1px solid #ccc; padding: 10px;margin: 0px 10px 10px 0'>

								<p>
								<strong>{{ $product->name }}</strong><br/>
								<small class='currency'>{{$product->price}}</small>
								</p>

								<p>

									@if(False)
									{{-- if($produtc->box) --}}
									<div class='input-append'>
										{{ Form::text($product->id.'-box', 0, array('class'=>'input-small reset-input qtdd','id'=>"qtd-caixas-$product->id",'onClick'=>'this.select()')) }}
										<span class='add-on'>caixas</span>
									</div>
									@endif

									<div class='input-append'>
										{{ Form::text($product->id.'-uni', 0, array('class'=>"input-small reset-input qtdd $product->category_slug", 'id'=>"qtd-unidades-$product->id",'onClick'=>'this.select()')) }}
										<span class='add-on'>unidades</span>
									</div>

								</p>

								<span id='preco-{{$product->id}}' style='display:none'>{{$product->price}}</span>
								<span id='box-{{$product->id}}' style='display:none'>{{$product->box}}</span>
								<span id='{{$product->slug}}' style='display:none'>{{$product->id}}</span>
							</li>
						@endif
					@endforeach
					@if(count($products)==0)
						<li class='text-info'>Você ainda não cadastrou nenhum produto.</li>
					@endif
					@if(!$found)
						<li class='text-warning'>Nenhum produto nessa categoria</li>
					@else
						<?php $found = False; ?>
					@endif

					</ul>
				</div>
			@endforeach
			@if(count($categories)==0)
				<p class='text-info'>Nenhuma categoria cadastrada</p>
			@endif

@section('script_functions')
	function run_calculations()
	{
		var 	caixas = 0;
		var 	unidades = 0;
		var 	box = 0;
		var 	preco = 0;
		var 	products = {{ $products->toJson() }} {{-- json_encode($products) --}};
		var 	total = 0;
		var 	id = 0;

		$.each(products, function(index, product){
			id = product.id;
			preco = parseFloat($('#preco-'+id).html());
			// Caixas
			var caixas = parseFloat($('#qtd-caixas-'+id).val());
			//var box = parseFloat($('#box-'+id).html());
			if(box)
			{
				total += parseFloat(box*caixas*preco);
			}

			// Unidades
			var unidades = parseFloat($('#qtd-unidades-'+id).val());
			total += parseFloat(unidades*preco);
		});
		// DEBUG
		//console.log(products);
		//console.log(total);

    // clean total
    //total = total.toFixed(2);

    	$('#total-hidden').html(total);
		$('#total').html(total).currency({
				region: 	'BRL',
				thousands: '.',
				decimal: ','
			});
		update_forms_total();
	}
	function update_forms_total()
	{
		var debit = parseFloat($('#debit').val());
		var credit = parseFloat($('#credit').val());
		var deposit = parseFloat($('#deposit').val());
		var bonus = parseFloat($('#bonus').val());
		var cash = parseFloat($('#cash').val());

		var total = debit + credit + deposit + bonus + cash;

		//alert(total);

		$('#forms-total').html(total).currency({
				region: 	'BRL',
				thousands: '.',
				decimal: ','
			});;

		var total_geral = parseFloat($('#total-hidden').html());
		var forms_total = total;

		if (total_geral != forms_total)
		{
			var difference = forms_total-total_geral;
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
@stop


@section('scripts2')
	<?php @parent ?>

	$(".reset-input").val(0);
	$('.qtdd').on('change', function(event) {
		run_calculations();
	});

	@foreach($categories as $category)

		$('#table-toggler-{{ $category->id }}').on('click', function(event){
			event.preventDefault();
			$('#table-wrapper-{{ $category->id }}').slideToggle();
		});
	@endforeach
@stop
