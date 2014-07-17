<?php

class SalesController extends BaseController
{
	public $restful = True;
	public $comparing_date = null;
	public $date_start = null;
	public $date_end = null;
	public $max_sales = 30;

	public function getASIndex()
	{
		$cds = Tenant::where('email','=',Auth::user()->email)->get();
		$view =  View::make('sales.asindex');
		$view->cds = $cds;
		return $view;
	}

	public function getEditSale($id)
	{
		$sale = Sale::find($id);
		// Users can only edit their own sales
		if(!Auth::user()->is_admin) {
			if ($sale->user_id != Auth::user()->id) {
				return Redirect::route('sales.asindex')->with('error', 'Você não pode editar uma venda criada por outro usuário.');
			}
		}

		$view = View::make('sales.new');

		$categories = Category::all();
		$products = Product::all();
		$view->items = $sale->items()->get();

		$view->sale = $sale;
		$view->categories = $categories;
		$view->products = $products;

		return $view;
	}

	public function getASSales($date, $payment_type, $tenants_ids)
	{
		if (Auth::user()->is_superadmin) {
			$sales = Sale::getSuperAdminSales($tenants_ids);
		} else {
			// get current tenant's sales
			$sales = Sale::all();
		}

		$this->comparing_date = $date;

		//TODO: if(!$show_deleted) {
		if(true) {
			$sales = $sales->filter(function($sale)
		    {
		        if ($sale->is_alive) {
		            return true;
		        }
		    });
		}

		// filter by date
		switch ($date) {
			case 'latest':
				$sales = $sales->take($this->max_sales);
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
			case 'Debito':
				$sales = $sales->filter(function($sale){
							if ($sale->debit > 0) {
								return $sale;
							}
						});
				break;
			case 'Credito':
				$sales = $sales->filter(function($sale){
							if ($sale->credit > 0) {
								return $sale;
							}
						});
				break;
			case 'Dinheiro':
				$sales = $sales->filter(function($sale){
							if ($sale->cash > 0) {
								return $sale;
							}
						});
				break;
			case 'Deposito':
				$sales = $sales->filter(function($sale){
							if ($sale->deposit > 0) {
								return $sale;
							}
						});
				break;
			case 'Bonus':
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

		// Users can only delete their own sales
		if(!Auth::user()->is_admin) {
			if ($sale->user_id != Auth::user()->id) {
				return Redirect::route('sales.asindex')->with('error', 'Você não pode deletar uma venda criada por outro usuário.');
			}
		}
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

	public function postNew($id=null)
	{
		$editing = false;
		if($id) {
			$sale = Sale::find($id);
			$editing = true;
			if($sale->tenant != Auth::user()->tenant) {
				die('Erro de autenticação');
			}
		}
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
			if($editing) {
				return Redirect::route('sales.edit',[$sale->id])
				->with('error', 'Utilize apenas números inteiros para as quantidades e números para as formas de pagamento.')
				->with('sale', $sale)
				->withInput();
			} else {
				return Redirect::route('sales.new')
				->with('error', 'Utilize apenas números inteiros para as quantidades e números para as formas de pagamento.')
				->withInput();
			}
			
		}
		if(!($editing)) {
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
		}

		$items = array();

		//print_r($products);

		$quantities_to_update = [];

		foreach($products as $id_type => $quantity)
		{
			list($id, $type) = explode('-', $id_type);

			$product = Product::find($id);
			/*
				if($type == 'box') {
					$total_quantity = $quantity*$product->box;
				} else {
					$total_quantity = $quantity;
				}
			*/
			$total_quantity = $quantity;
			if($total_quantity != 0)
			{
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
			/*
				OLD VERSION WITH BOXES
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
			*/

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
			if(!$editing){
				$sale->delete();
			}
			
			if($editing) {
				return Redirect::route('sales.edit',[$sale->id])
				->with('error', "Os valores das formas de pagamento não batem com o valor total do pedido! Cheque os valores e tente novamente.")
				->with('sale', $sale)
				->withInput();
			} else {
				return Redirect::route('sales.new')
				->with('error', "Os valores das formas de pagamento não batem com o valor total do pedido! Cheque os valores e tente novamente.")
				->withInput();
			}
			
		}
		// ALL CLEAR

		// Update sale if editing
		if($editing)
		{
			// add all quantities of old sale
			$old_items = $sale->items()->get();
			foreach ($old_items as $item) {
				$product = Product::find($item->product_id);
				$product->update_quantities($item->quantity,$item->quantity,'add');
			}
			// remove all old items of this sale
			$sale->items()->delete();
			// update formas de pagamento
			$sale->cash = Input::get('cash');
			$sale->credit = Input::get('credit');
			$sale->debit = Input::get('debit');
			$sale->deposit = Input::get('deposit');
			$sale->bonus = Input::get('bonus');
			$sale->save();
		}

		foreach ($items as $item_array) {
			$item = new Item($item_array);
			$sale->items()->save($item);
		}

		// Update (new) quantities, everything was succesfull, redirect
		foreach ($quantities_to_update as $product_id => $quantity) {
			$product = Product::find($product_id);
			$product->update_quantities($item->quantity,$item->quantity,'subtract');
			//$product->quantity_in_stock -= $quantity;
			//$product->save();
		}

		if($editing) {
			return Redirect::route('sales.focus', [$sale->id])->with('notice', 'Venda atualizada com sucesso.');
		} else {
			return Redirect::route('sales.new')->with('notice', 'Venda gerada com sucesso.');
		}
		
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