

<h3>Pedido Atual</h3>

<table class='table table-condensed' id='pedido-table'>
	
</table>

<hr/>
<div class="input-prepend">
	<span class="add-on" style='padding: 10px 0'><i class='icon icon-filter'></i> Comece a digitar para encontrar os produtos</span>
	<input type='text' id='filter-products' style='padding: 10px; font-weight: bold;' />
</div>
<button class='btn btn-mini btn-warning pretty-button' id='show-all'>Mostrar todos</button>
<hr/>



<?php $found = False ?>
<table class='table table-condensed'>
@foreach($categories as $category)

	<!--
	<a class='no-format' href='#' id='table-toggler-{{ $category->id }}'>
		<p class='lead'>
		<span class="badge badge-warning qtd-badge" id='qtd-badge-{{$category->id}}' style='display:none'>0</span> {{ $category->name }}
		</p>
	</a>
	-->
		<tbody>
		<tr class='cat-tr'>
			<td colspan="2">
			<span class="badge badge-warning qtd-badge" id='qtd-badge-{{$category->id}}' style='display:none;float:left'>0</span>
				<h4 class="text-info">{{$category->name}}</h4>
			</td>
		</tr>
		@foreach($products as $product)
			@if($product->category == $category)
				<?php $found = True ?>
				
				<tr class='product' id='tr-{{$product->id}}'>
					<td>
						<label class='label'>{{$product->category->name}}</label>
						<span class='lead name'>{{ $product->name }}</span>
						<em><small class="currency text-info">{{$product->price}}</small></em>
					</td>
					<td>
						<div class='input-append'>
							{{ Form::text($product->id.'-uni', 0, array('class'=>"input-small reset-input qtdd cat-id-$category->id $product->category_slug", 'id'=>"qtd-unidades-$product->id",'onClick'=>'this.select()')) }}
							
							<span class='add-on'>unidades</span>
							<span class='cat-id' style='display:none'>{{$product->category->id}}</span>
							<span class="product-name" style='display:none'>{{$product->name}}</span>
							<span class='product-price' style='display:none'>{{$product->price}}</span>
							<span class='product-id' style='display:none'>{{$product->id}}</span>
						</div>
						{{-- Meta info for js --}}
						<span id='preco-{{$product->id}}' style='display:none'>{{$product->price}}</span>
						<span id='{{$product->slug}}' style='display:none'>{{$product->id}}</span>
					</td>


				</tr>
					
					
			@endif
		@endforeach
		@if(count($products)==0)
			<tr><td><p class='text-info'>Você ainda não cadastrou nenhum produto.</p></td></tr>
		@endif
		@if(!$found)
			<tr><td><p class='text-warning'>Nenhum produto nessa categoria</p></td></tr>
		@else
			<?php $found = False; ?>
		@endif

@endforeach

</tbody>
</table>

@if(count($categories)==0)
	<tr><td><p class='text-info'>Nenhuma categoria cadastrada</p></td></tr>
@endif

@section('script_functions')
	function run_calculations()
	{
		var 	caixas = 0;
		var 	unidades = 0;
		var 	box = 0;
		var 	preco = 0;
		var 	products = {{ $products->toJson() }};
		var 	total = 0;
		var 	id = 0;

		$.each(products, function(index, product){
			id = product.id;
			preco = parseFloat($('#preco-'+id).html());

			// Unidades
			var unidades = parseFloat($('#qtd-unidades-'+id).val());
			total += parseFloat(unidades*preco);
		});

    // clean total
    total = total.toFixed(2);

    	$('#total-hidden').html(total);
		$('#total').html(total).currency({
				region: 	'BRL',
				thousands: '.',
				decimal: ','
			});
		
	}
	
@stop


@section('scripts2')
	<?php @parent ?>

	@if(!isset($sale))$(".reset-input").val(0);@endif
	$('#filter-products').val('');

	var pedido_table_list = [];

	function global_update_qtd_badge()
	{
		// Zerar badges
		$('.qtd-badge').html(0).hide();
		
		qtd_inputs = $('.qtdd');
		$.each(qtd_inputs,function(index,value){
			// get val
			qtd = parseInt($(value).val());
			// if > 0 show cat badge
			if (qtd > 0) 
			{
				// get cat badge id
				cat_id = $(value).siblings('span.cat-id').html();
				id_badge = '#qtd-badge-'+cat_id;
				current_qtd_badge = parseInt($(id_badge).html());
				new_qtd = qtd + current_qtd_badge;
				$(id_badge).show().html(new_qtd);
				// add container class
				li = $(this).parents('tr').css('background-color','#eed');
			} else {
				$(this).parents('tr').css('background-color','#fff');
			}
			
		});
	}

	function reconstruct_pedido_table() {
		// rebuild pedido-table with pedido_table_list
		var pedido_table = $('#pedido-table');
		if(pedido_table_list.length == 0) {
			// no items in pedido, build plain table
			pedido_table.html("<tr><td><p class='text-info'>Nenhum produto adicionado ao pedido</p></td><tr>");
		} else {
			pedido_table.html('');
			$.each(pedido_table_list, function(i,object){
				object_total = parseFloat(object.price) * parseInt(object.qtd);
				pedido_table.append("<tr><td><strong>"+object.name+"</strong></td><td>R$"+object.price+" &times; <label class='label'>"+object.qtd+" unidades</label></td><td><strong>R$"+object_total+"</strong></td></tr>");
			});
		}
		
	}

	function add_to_pedido_table_list(new_obj) {
		var exists = false;
		console.debug(pedido_table_list);
		$.each(pedido_table_list, function(i, old_obj) {
			if(old_obj.id == new_obj.id) {
				// object already exists in pedido
				exists = true;
				if(new_obj.qtd == 0) {
					// remove object from list
					pedido_table_list.splice(i,1);
				} else {
					old_obj.qtd = new_obj.qtd;
				}
				
			}
		});
		if(!exists && new_obj.qtd != 0) {
			// new object, add to list
			pedido_table_list.push(new_obj);
		}
	}

	function update_pedido(item) {
		qtd_input = $(item);
		product_name = qtd_input.siblings('span.product-name').html();
		product_price = qtd_input.siblings('span.product-price').html();
		product_id = qtd_input.siblings('span.product-id').html();
		qtd = qtd_input.val();
		console.log('Produto: '+ product_name + ' ID:'+product_id +' QTD: '+qtd+' for: R$'+product_price);

		// add to pedido table list
		add_to_pedido_table_list({
			'id': 	product_id,
			'qtd': 	qtd,
			'price': product_price,
			'name':  product_name
		});

		// reconstruct pedido_table
		reconstruct_pedido_table();
	}

	$('.qtdd').on('keyup', function(event) {

		// If qtdd > 0 search product in pedido-table and update qtd, 
		// else (qtdd == 0) search product in pedido-table and remove it from there
		update_pedido($(this));
		
		global_update_qtd_badge();

		run_calculations();
	});

	@foreach($categories as $category)

		$('#table-toggler-{{ $category->id }}').on('click', function(event){
			event.preventDefault();
			$('#table-wrapper-{{ $category->id }}').slideToggle();
		});
	@endforeach

	function show_all_products() {
		$('#filter-products').val('');
		trs = $('tr.product');
		cat_trs = $('tr.cat-tr');
		cat_trs.show();
		trs.show();
	}

	function hide_all_products() {
		$('#filter-products').val('');
		trs = $('tr.product');
		cat_trs = $('tr.cat-tr');
		cat_trs.hide();
		trs.hide();
	}

	$('#filter-products').on('click', function(e){
		$(this).select();
	});

	$('#filter-products').on('keyup', function(e){
		//alert($(this).val());
		cur = $(this).val();
		trs = $('tr.product');
		cat_trs = $('tr.cat-tr');
		cat_trs.hide();

		trs.hide();

		search_array = [];
		$.each(trs, function(i, tr){
			item_name = $(tr).children('td').children('span.name').html();
			item_id = $(tr).attr('id');
			search_array.push({name: item_name, id: item_id});
		});

		var options = {
			keys: ['name'],
			id: 'id',
			threshold: 0.1,
			distance: 1000
		}
		var f = new Fuse(search_array, options);
		var results = f.search(cur);
		//console.debug(results);
		
		$.each(results, function(i, tr_id){
			$('#'+tr_id).show();
		});

	});

	$('#filter-products').on('blur', function() {
		if($(this).val() == '') {
			hide_all_products();
		}
	});

	$('#show-all').on('click', function(e) {
		show_all_products();
		e.preventDefault();
	});

	reconstruct_pedido_table();
	hide_all_products();

@stop
