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

	public function getStock()
	{
		$categories = Category::all();
		$view = View::make('snapshots.stock');
		$view->categories = $categories;

		$full_product_list = [];
		$product_list_in_stock = [];
		$product_list_out_of_stock = [];

		foreach ($categories as $category) {
			$cat_products = $category->products;

			$category_has_at_least_one_product = false;
			$cat_products_in_stock = $category->products->filter(function($product){
				if($product->quantity_in_stock > 0)
				{
					//$category_has_at_least_one_product = true;
					return $product;
				}
			});

			$full_product_list[$category->name] = $cat_products;

			if($category_has_at_least_one_product)
			{
				$product_list_in_stock[$category->name] = $cat_products_in_stock;
			} else {
				$product_list_out_of_stock[$category->name] = $cat_products;
			}
			$category_has_at_least_one_product = false;
			
		}
		$view->product_list = $full_product_list;
		$view->product_list_in_stock = $product_list_in_stock;
		$view->product_list_out_of_stock = $product_list_out_of_stock;

		return $view;
	}

	public function getNew($type='snapshot')
	{
		$products = Product::all();
		$categories = Category::all();
		$view = View::make('snapshots.new');
		$view->products = $products;
		$view->categories = $categories;

		switch ($type) {
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

	public function postNew($type='snapshot')
	{
		//if($entry != False) { $entry = True; }
		$rules = array();
		$inputs = Input::all();
		foreach($inputs as $product => $quantity)
		{
			if ($product != '_token') {
				$rules[$product] = 'integer';	
			}
		}
		$validation = Validator::make($inputs, $rules);

		if ($validation->fails())
		{
			switch ($type) {
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

		$snapshot = Snapshot::create(array(
			'tenant_id'	=>	Auth::user()->tenant_id,
			'is_alive'	=>	True,
			'type'		=> 	$type
			//'entry'		=>	False
		));

		$parts = array();
		$snapshot_id = $snapshot->id;

		foreach($inputs as $id_type => $quantity)
		{
			if ($id_type == '_token') { continue; }

			$list = explode('-', $id_type);
			
			$id = $list[0]; $type = $list[1];

			$product = Product::find($id);

			if($type == 'box') {
				$total_quantity = $quantity*$product->box;
			} else {
				$total_quantity = $quantity;
			}

			if($total_quantity != 0)
			{
				// Add total quantity to current stock
				$product->quantity_in_stock += $total_quantity;
				$product->save();

				if(array_key_exists($product->id, $parts))
				{
					// a part for this product already exists, sum the quantities
					$parts[$product->id]['quantity'] += $total_quantity;
				} else {
					$parts[$product->id] = array(
						'tenant_id'		=>	Auth::user()->tenant_id,
						'snapshot_id'	=>	$snapshot_id,
						'product_id'	=>	$product->id,
						'current_price'	=>	$product->price,
						'quantity'		=>	$total_quantity,
						'is_alive'		=>	True
					);
				}
			}
			
		}
		// turn array to actual part models before adding
		foreach ($parts as $part_item_array) {
			Part::create($part_item_array);
		}

		$notice = '';
		switch ($type) {
			case 'snapshot':
				$notice = 'Fotografia criada com sucesso';
				break;
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

}