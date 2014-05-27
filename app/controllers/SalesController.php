<?php

class SalesController extends BaseController
{
	public $restful = True;
	public $comparing_date = null;
	public $date_start = null;
	public $date_end = null;

	public function getIndex($filter)
	{
		$sales = Sale::all()->take(50);	//TODO: are we gonna cap the results? implement a "show more" button

		if ($filter == 'specific_date') {
			$start_date = Input::get('start_date');
			$end_date = Input::get('end_date');
			if ($start_date == null || $end_date == null) {
				return Redirect::route('sales.index','latest')
				->with('error', 'Escolha um período para as vendas');
			}
			// Build carbon object
			list($start_day,$start_month,$start_year) = explode('/', $start_date);
			list($end_day,$end_month,$end_year) = explode('/', $end_date);
			$start_date_obj = Carbon::createFromDate($start_year,$start_month,$start_day);
			$end_date_obj = Carbon::createFromDate($end_year,$end_month,$end_day);


			// check if end_date < start_date
			if ($end_date_obj->format('Y-m-d') < $start_date_obj->format('Y-m-d')) {
				return Redirect::route('sales.index','latest')
				->with('error', 'A data final deve ser maior que a data inicial.');
			}

			$same_dates = false;
			if ($start_date == $end_date) {
				$same_dates = true;
			}

			list($start_day,$start_month,$start_year) = explode('/', $start_date);
			list($end_day,$end_month,$end_year) = explode('/', $end_date);

			foreach ($sales as $key => $sale) {
				if ($same_dates) {
					if ($end_date_obj->format('Y-m-d') == $sale->created_at->format('Y-m-d'))
					{
						continue;
					} else {
						$sales->forget($key);
					}
				} else {
					if ($sale->created_at->format('Y-m-d') >= $start_date_obj->format('Y-m-d') 
						&& $sale->created_at->format('Y-m-d') <= $end_date_obj->format('Y-m-d')) {
						continue;
					} else {
						$sales->forget($key);
					}
				}
			}

		} else {

			switch ($filter) {
				case 'latest':
					break;
				case 'today':
					$sales = $sales->filter(function($sale){
						if ($sale->created_at->format('Y-m-d') == Carbon::today()->format('Y-m-d')) {
							return $sale;
						}
					});
					break;
				case 'yesterday':
					$sales = $sales->filter(function($sale){
						if ($sale->created_at->format('Y-m-d') == Carbon::yesterday()->format('Y-m-d')) {
							return $sale;
						}
					});
					break;
				case 'month':
					$sales = $sales->filter(function($sale){
						if ($sale->created_at->format('Y-m') == Carbon::today()->format('Y-m')) {
							return $sale;
						}
					});
					break;
			}
		}

		$view = View::make('sales.index');
		$view->sales = $sales;

		switch ($filter) {
			case 'latest':
				$filter_message = "(50 últimas)";
				break;
			case 'specific_date':
				$filter_message = "no período de $start_date a $end_date";
				break;
			case 'today':
				$filter_message = "de Hoje";
				break;
			case 'yesterday':
				$filter_message = "de Ontem";
				break;
			case 'month':
				$filter_message = "desse Mês";
				break;
			default:
				$filter_message = "";
				break;
		}

		$view->filter_message = $filter_message;

		return $view;
	}

	public function getASIndex()
	{
		return View::make('sales.asindex');
	}

	public function getASSales($date, $payment_type)
	{
		$sales = Sale::all();

		$this->comparing_date = $date;

		// filter by date
		switch ($date) {
			case 'latest':
				$sales = $sales->take(200);
				break;
			case 'today':
				$sales = $sales->filter(function($sale){
							if ($sale->created_at->format('Y-m-d') == Carbon::today()->format('Y-m-d')) {
								return $sale;
							}
						});
				break;
			case 'yesterday':
				$sales = $sales->filter(function($sale){
							if ($sale->created_at->format('Y-m-d') == Carbon::yesterday()->format('Y-m-d')) {
								return $sale;
							}
						});
				break;
			case 'month':
				$sales = $sales->filter(function($sale){
							if ($sale->created_at->format('Y-m') == Carbon::today()->format('Y-m')) {
								return $sale;
							}
						});
				break;
			case 'year':
				$sales = $sales->filter(function($sale){
							if ($sale->created_at->format('Y') == Carbon::today()->format('Y')) {
								return $sale;
							}
						});
				break;
			default:
				// A date like 03/04/1986 or like 03/04/1986*24/04/1986
				$dates_array = explode('*', $this->comparing_date); //list($this->date_start,$this->date_end)
				if (count($dates_array) > 1) {
					// Period date
					$this->date_start = $dates_array[0];
					$this->date_end = $dates_array[1];

					$sales = $sales->filter(function($sale) {
						list($start_day,$start_month,$start_year) = explode('-', $this->date_start);
						list($end_day,$end_month,$end_year) = explode('-', $this->date_end);
						$start_date_obj = Carbon::createFromDate($start_year,$start_month,$start_day);
						$end_date_obj = Carbon::createFromDate($end_year,$end_month,$end_day);
						$clean_sale_date = Carbon::createFromDate($sale->created_at->year,$sale->created_at->month,$sale->created_at->day);
						if ($clean_sale_date->between($start_date_obj,$end_date_obj)) {
							return $sale;
						}
					});
				} else {
					// Specific date
					$sales = $sales->filter(function($sale) {
						list($start_day,$start_month,$start_year) = explode('-', $this->comparing_date);
						$start_date_obj = Carbon::createFromDate($start_year,$start_month,$start_day);
						if($sale->created_at->format('Y-m-d') == $start_date_obj->format('Y-m-d')) {
							return $sale;
						}
					});
				} 
				break;
		}

		// continue filtering by payment_type
		switch ($payment_type) {
			case 'debit':
				$sales = $sales->filter(function($sale){
							if ($sale->debit > 0) {
								return $sale;
							}
						});
				break;
			case 'credit':
				$sales = $sales->filter(function($sale){
							if ($sale->credit > 0) {
								return $sale;
							}
						});
				break;
			case 'cash':
				$sales = $sales->filter(function($sale){
							if ($sale->cash > 0) {
								return $sale;
							}
						});
				break;
			case 'deposit':
				$sales = $sales->filter(function($sale){
							if ($sale->deposit > 0) {
								return $sale;
							}
						});
				break;
			case 'bonus':
				$sales = $sales->filter(function($sale){
							if ($sale->bonus > 0) {
								return $sale;
							}
						});
				break;
			default:
				break;
		}

		return Response::json($sales);
	}

	public function getDeleteSale($id)
	{
		$sale = Sale::findOrFail($id);
		$sale->is_alive = false;
		$sale->order_number_before_delete = $sale->order_number;
		$sale->order_number = NULL;
		$sale->deleted_by = Auth::user()->id;
		$sale->save();
		// update the quantities again

		$items = $sale->items()->get();
		// get items - add quantities
		foreach ($items as $item) {
			$product = $item->product;
			$product->update_quantities($item->quantity,$item->quantity,'add');
			$product->save();
			$item->is_alive = False;
			$item->save();
		}

		return Redirect::route('sales.asindex')->with('notice', 'Venda deletada com sucesso.');
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
					$rules[$field] = 'unique:sales';
					break;
					
				case '_token':
				case 'notes':
					break;
				case 'ativacoes_input':
					$rules[$field] = 'numeric|required';
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
				->with('error', 'Utilize apenas números inteiros para as quantidades e números para as formas de pagamento. O número do pedido deve ser único.')
				->withInput();
		}

		$sale = Sale::create(array(
			'tenant_id'	=>  Auth::user()->tenant_id,
			'user_id'	=>	Auth::user()->id,
			'is_alive'	=>	True,
			'debit'		=>	Input::get('debit'),
			'credit'		=>	Input::get('credit'),
			'bonus'		=>	Input::get('bonus'),
			'cash'		=>	Input::get('cash'),
			'deposit'		=>	Input::get('deposit'),
			'order_number'	=>	Input::get('order_number'),
			'notes'	=> Input::get('notes')
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
						'virtual_quantity' => $total_quantity,
						'is_alive'		=>	True
					);
					$quantities_to_update[$product->id] = $total_quantity;
				}
			}
		}

		// validate sum of payment methods
		$temp_sum = 0;
		foreach ($items as $item) {
			$temp_sum += $item['current_price'] * $item['quantity']; // quantity x virtual_quantity
		}
		$temp_sum = round($temp_sum,2);
		$sum_of_payments = round(Input::get('debit') + Input::get('credit') + Input::get('cash') + Input::get('deposit') + Input::get('bonus'),2);
		if($temp_sum != $sum_of_payments)
		{
			// error in calculation! blow up!
			$sale->delete();

			// #DEBUG
			//die($temp_sum != $sum_of_payments);
			//die('temp_sum'. var_dump($temp_sum));
			//die('sum_of_payments'. var_dump($sum_of_payments));
			//die('temp_sum != $sum_of_payments: ' . ($temp_sum != $sum_of_payments) . "\ntemp_sum: $temp_sum" . "\nsum_of_payments: $sum_of_payments\n".var_dump($sum_of_payments).var_dump($temp_sum));

			return Redirect::route('sales.new')
				->with('error', "Os valores das formas de pagamento não batem com o valor total do pedido! Cheque os valores e tente novamente.")
				->withInput();
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
			$product->update_quantities($item->quantity,$item->quantity,'subtract');
			//$product->quantity_in_stock -= $quantity;
			//$product->save();
		}

		return Redirect::route('sales.new')->with('notice', 'Venda gerada com sucesso.');
		
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