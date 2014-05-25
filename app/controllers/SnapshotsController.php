<?php

class SnapshotsController extends BaseController
{
	public $restful = true;

	public function getIndex()
	{
		$snapshots = Snapshot::all();
		$view = View::make('snapshots.index');
		$view->snapshots = $snapshots;
		return $view;
	}

	public function getStock($filter='none')
	{
		$categories = Category::all();
		$view = View::make('snapshots.stock');
		$view->categories = $categories;

		$full_product_list = [];
		$product_list_in_stock = [];
		$product_list_out_of_stock = [];
		$total_stock_value = 0;
		$total_virtual_stock = 0;
		$total_ambos_stock = 0;

		foreach ($categories as $category) {
			$cat_products = $category->products;

			foreach ($category->products as $product) {
				$total_stock_value += max($product->quantity_in_stock,0) * $product->price;
				$total_virtual_stock += max($product->quantity_in_virtual,0) * $product->price;

				// get minimum quantity between in_stock and in_virtual
				// then if number is negative, turn it to zero max($num,0)
				$ambos_quantity = max(min($product->quantity_in_stock,$product->quantity_in_virtual),0);

				$total_ambos_stock += $ambos_quantity * $product->price;
			}
			/* DISABLED
			$cat_products_in_stock = $category->products->filter(function($product){
				if($product->quantity_in_stock != 0 || $product->quantity_in_virtual != 0)
				{
					return $product;
				}
			});
			

			if(count($cat_products_in_stock) > 0)
			{
				$product_list_in_stock[$category->name] = $cat_products_in_stock;
			} else {
				$product_list_out_of_stock[$category->name] = $cat_products;
			}
			*/

			// Full product list
			$full_product_list[$category->name] = $cat_products;

			$cat_products_sobrando_virtual = $category->products->filter(function($product){
				if($product->quantity_in_stock < $product->quantity_in_virtual)
				{
					return $product;
				}
			});
			$cat_products_faltando_virtual = $category->products->filter(function($product){
				if($product->quantity_in_stock > $product->quantity_in_virtual)
				{
					return $product;
				}
			});
			if (count($cat_products_sobrando_virtual) > 0) {
				$cat_products_sobrando_virtual_list[$category->name] = $cat_products_sobrando_virtual;
			}
			if (count($cat_products_faltando_virtual) > 0) {
				$cat_products_faltando_virtual_list[$category->name] = $cat_products_faltando_virtual;
			}

		}
		// Choose the list
		switch ($filter) {
			case 'none':
				$view->product_list = $full_product_list;
				$view->list_option = 'todos';
				break;
			case 'sobrando_virtual':
				$view->product_list = $cat_products_sobrando_virtual_list;
				$view->list_option = 'sobrando_virtual';
				break;
			case 'faltando_virtual':
				$view->product_list = $cat_products_faltando_virtual_list;
				$view->list_option = 'faltando_virtual';
				break;
		}

		$view->total_stock_value = $total_stock_value;
		$view->total_virtual_stock = $total_virtual_stock;
		$view->total_ambos_stock = $total_ambos_stock;

		return $view;
	}

	public function getNew($snapshot_type='snapshot')
	{
		$products = Product::all();
		$categories = Category::all();
		$view = View::make('snapshots.new');
		$view->products = $products;
		$view->categories = $categories;

		switch ($snapshot_type) {
			case 'entry':
				$view->entry_snapshot = True;
				break;
			case 'baixa':
				$view->baixa_snapshot = True;
				break;
		}
		/*
		if ($type == 'entry') {
			$view->entry_snapshot = True;
		}
		*/

		return $view;
	}

	public function postNew($snapshot_type='snapshot')
	{
		//if($entry != False) { $entry = True; }
		$rules = array();
		$inputs = Input::all();
		foreach($inputs as $product => $quantity)
		{
			if ($product == '_token' || $product == 'stock_option') {
				continue;
			} else {
				$rules[$product] = 'integer';
			}
		}
		$validation = Validator::make($inputs, $rules);

		if ($validation->fails())
		{
			switch ($snapshot_type) {
				case 'snapshot':
					return Redirect::route('snapshots.new')
						->with('error', 'Utilize apenas números inteiros para as quantidades.');
					break;
				case 'entry':
					return Redirect::route('snapshots.new')
						->with('error', 'Utilize apenas números inteiros para as quantidades.')
						->with('entry_snapshot', true);
					break;
				case 'baixa':
					return Redirect::route('snapshots.new')
						->with('error', 'Utilize apenas números inteiros para as quantidades.')
						->with('baixa_snapshot', true);
					break;
			}
		}

		// get option
		$stock_option = Input::get('stock_option');

		$snapshot = Snapshot::create(array(
			'tenant_id'	=>	Auth::user()->tenant_id,
			'is_alive'	=>	True,
			'type'		=> 	$snapshot_type,
			'user_id'	=>	Auth::user()->id
			//'entry'		=>	False
		));

		$parts = array();
		$snapshot_id = $snapshot->id;

		foreach($inputs as $id_type => $quantity)
		{
			if ($id_type == '_token' || $id_type == 'stock_option') { continue; }

			$list = explode('-', $id_type);

			$id = $list[0]; $type = $list[1];

			$product = Product::find($id);
			$total_quantity = 0;
			if($type == 'box') {
				$total_quantity = $quantity*$product->box;
			} else {
				$total_quantity = $quantity;
			}

			if($total_quantity != 0)
			{
				// Update quantity in current stock
				switch ($snapshot_type) {
					case 'baixa':
						// Reduce quantities
						switch ($stock_option) {
							case 'virtual':
								$product->update_quantities(0,$total_quantity,'subtract');
								break;
							case 'ambos':
								$product->update_quantities($total_quantity,$total_quantity,'subtract');
								break;
							case 'real':
								$product->update_quantities($total_quantity,0,'subtract');
								break;
						}
						break;
					case 'entry':
						// Add quantities
						switch ($stock_option) {
							case 'virtual':
								$product->update_quantities(0,$total_quantity,'add');
								break;
							case 'ambos':
								$product->update_quantities($total_quantity,$total_quantity,'add');
								break;
							case 'real':
								$product->update_quantities($total_quantity,0,'add');
								break;
						}
						break;
				}
				//$product->save();	im saving in the update_quantities method

				if(array_key_exists($product->id, $parts))
				{
					// a part for this product already exists, sum the quantities
					// TODO: remove this code (all reference to box stuff)
					$parts[$product->id]['quantity'] += $total_quantity;
				} else {
					/*
					$parts[$product->id] = array(
						'tenant_id'		=>	Auth::user()->tenant_id,
						'snapshot_id'	=>	$snapshot_id,
						'product_id'	=>	$product->id,
						'current_price'	=>	$product->price,
						'is_alive'		=>	True
					);
					*/
					switch ($stock_option) {
						case 'ambos':
							/*
							$parts[$product->id]['quantity'] = $total_quantity;
							$parts[$product->id]['virtual_quantity'] = $total_quantity;
							*/
							$parts[$product->id] = array(
								'tenant_id'		=>	Auth::user()->tenant_id,
								'snapshot_id'	=>	$snapshot_id,
								'product_id'	=>	$product->id,
								'current_price'	=>	$product->price,
								'is_alive'		=>	True,
								'quantity'		=>	$total_quantity,
								'virtual_quantity' => $total_quantity
							);
							break;
						case 'virtual':
							/*
							$parts[$product->id]['quantity'] = 0;
							$parts[$product->id]['virtual_quantity'] = $total_quantity;
							*/
							$parts[$product->id] = array(
								'tenant_id'		=>	Auth::user()->tenant_id,
								'snapshot_id'	=>	$snapshot_id,
								'product_id'	=>	$product->id,
								'current_price'	=>	$product->price,
								'is_alive'		=>	True,
								'quantity'		=>	0,
								'virtual_quantity' => $total_quantity
							);
							break;
						case 'real':
							/*
							$parts[$product->id]['quantity'] = $total_quantity;
							$parts[$product->id]['virtual_quantity'] = 0;
							*/
							$parts[$product->id] = array(
								'tenant_id'		=>	Auth::user()->tenant_id,
								'snapshot_id'	=>	$snapshot_id,
								'product_id'	=>	$product->id,
								'current_price'	=>	$product->price,
								'is_alive'		=>	True,
								'quantity'		=>	$total_quantity,
								'virtual_quantity' => 0
							);
							break;
					}
				}
			}

		}
		// turn array to actual part models before adding
		foreach ($parts as $part_item_array) {
			/*
			$item = new Item($item_array);
			$sale->items()->save($item);
			*/
			$part = new Part($part_item_array);
			$snapshot->parts()->save($part);
			//Part::create($part_item_array);
		}


		$notice = '';
		switch ($snapshot_type) {
			case 'entry':
				$notice = 'Pedido de reposição registrado com sucesso';
				break;
			case 'baixa':
				$notice = 'Baixa realizada com sucesso';
				break;
		}

		return Redirect::route('snapshots.focus', array($snapshot->id))->with('notice', $notice);
	}

	public function getFocus($id)
	{
		$snapshot = Snapshot::find($id);
		$parts = $snapshot->parts()->get();
		$view = View::make('snapshots.focus');
		$view->snapshot = $snapshot;
		$view->parts = $parts;
		return $view;
	}

	public function getDeleteSnapshot($id)
	{
		$snapshot = Snapshot::findOrFail($id);
		$parts = $snapshot->parts()->get();
		$type = $snapshot->type;

		if ($snapshot->is_alive) {
			foreach ($parts as $part) {
				$product = $part->product;
				switch ($type) {
					case 'baixa':
						# Reverter baixa, add quantidades
						//$product->quantity_in_stock += $part->quantity;
						$product->update_quantities($part->quantity,$part->virtual_quantity,'subtract');
						break;
					case 'entry':
					case 'snapshot':
						# Reverter entrada, subtract quantidades
						//$product->quantity_in_stock -= $part->quantity;
						$product->update_quantities($part->quantity,$part->virtual_quantity,'add');
						break;
				}
				$product->save();
			}
		}

		$snapshot->deleted_by = Auth::user()->id;
		$snapshot->is_alive = False;
		$snapshot->save();

		return Redirect::route('snapshots.index')->with('notice', "Deletado com sucesso");

	}

}
