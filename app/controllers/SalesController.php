<?php

class SalesController extends BaseController
{
	public $restful = True;

	public function getIndex()
	{
		$sales = Sale::all();

		$view = View::make('sales.index');
		$view->sales = $sales;

		return $view;
	}

	public function getNew()
	{
		$view = View::make('sales.new');
		$categories = Category::all();
		$products = Product::all();

		$view->categories = $categories;
		$view->products = $products;

		return $view;
	}

	public function postNew()
	{
		$rules = array();
		$inputs = Input::all();
		$products = array();
		foreach($inputs as $field => $value)
		{
			switch ($field) {
				case 'debit':
				case 'credit':
				case 'cash':
				case 'deposit':
				case 'bonus':
					// formas de pagamento numeric
					$rules[$field] = 'numeric';
					break;
				case 'order_number':
					// numero do pedido obrigatorio
					$rules[$field] = 'required';
					break;
				case '_token':
					break;
				default:
					// Quantidades de produtos
					$rules[$field] = 'integer';
					$products[$field] = $value;
					break;
			}
		}
		$validation = Validator::make($inputs, $rules);

		if ($validation->fails())
		{
			//return print_r($validation->errors);
			return Redirect::route('sales.new')
				->with('error', 'Utilize apenas números inteiros para as quantidades e números para as formas de pagamento. O número do pedido é obrigatório.');
		}

		$sale = Sale::create(array(
			'tenant_id'	=>  Auth::user()->tenant_id,
			'is_alive'	=>	True,
			'debit'		=>	Input::get('debit'),
			'credit'		=>	Input::get('credit'),
			'bonus'		=>	Input::get('bonus'),
			'cash'		=>	Input::get('cash'),
			'deposit'		=>	Input::get('deposit'),
			'order_number'	=>	Input::get('order_number')
		));

		$items = array();

		//print_r($products);

		$quantities_to_update = [];

		foreach($products as $id_type => $quantity)
		{
			list($id, $type) = explode('-', $id_type);

			$product = Product::find($id);

			if($type == 'box') {
				$total_quantity = $quantity*$product->box;
			} else {
				$total_quantity = $quantity;
			}

			if($total_quantity != 0)
			{
				if(array_key_exists($product->id, $items))
				{
					// a part for this product already exists, sum the quantities
					$items[$product->id]['quantity'] += $total_quantity;
					$quantities_to_update[$product->id] += $total_quantity;

				} else {
					$items[$product->id] = array(
						'tenant_id'		=>	Auth::user()->tenant_id,
						'sale_id'		=>	$sale->id,
						'product_id'	=>	$product->id,
						'current_price'	=>	$product->price,
						'quantity'		=>	$total_quantity,
						'is_alive'		=>	True
					);
					$quantities_to_update[$product->id] = $total_quantity;
				}
			}
		}

		// validate sum of payment methods
		$temp_sum = 0;
		foreach ($items as $item) {
			$temp_sum += $item['current_price'] * $item['quantity'];
		}
		$sum_of_payments = Input::get('debit') + Input::get('credit') + Input::get('cash') + Input::get('deposit') + Input::get('bonus');
		if($temp_sum != $sum_of_payments)
		{
			// error in calculation! blow up!
			$sale->delete();
			return Redirect::route('sales.new')
				->with('error', 'Os valores das formas de pagamento não batem com o valor total do pedido! Cheque os valores e tente novamente.');
		}

		// Save sale and its items

		//$sale->items()->save($items);
		foreach ($items as $item_array) {
			$item = new Item($item_array);
			$sale->items()->save($item);
		}

		// Update quantities, everything was succesfull, redirect
		foreach ($quantities_to_update as $product_id => $quantity) {
			$product = Product::find($product_id);
			$product->quantity_in_stock -= $quantity;
			$product->save();
		}

		return Redirect::route('sales.index')->with('notice', 'Venda gerada com sucesso.');
		
	}

	public function getFocus($id)
	{
		$sale = Sale::find($id);
		$items = $sale->items()->get();

		$view = View::make('sales.focus');

		$view->sale = $sale;
		$view->items = $items;
		$view->payments = $sale->get_payments();
		return $view;
	}
}