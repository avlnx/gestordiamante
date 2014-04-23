<?php

class ProductsController extends BaseController
{
	public $restful = true;

	public function getIndex()
	{
		$tenant_id = Auth::user()->tenant_id;
		$products = Product::all();
		$categories = Category::all();

		$view = View::make('products.index');
		$view->products = $products;
		$view->categories = $categories;

		return $view;
	}

	public function getFocus($id)
	{
		$product = Product::find($id);

		if ($product->tenant_id != Auth::user()->tenant_id) {
			# product doesn't belong to this user
			die('Acesso não autorizado');
		}

		$view = View::make('products.focus');
		$view->product = $product;

		return $view;
	}

	public function getNew()
	{
		// TODO: get categories for this tenant only
		$categories = Category::all();
		$category_list = array();
		foreach ($categories as $category)
		{
			$category_list[$category->id] = $category->name;
		}

		$view = View::make('products.new');
		$view->categories = $category_list;

		return $view;
	}

	public function postNew()
	{
		// Process post of new product in MODEL

		$rules = array(
			'name'	=>	'required',
			'description'	=>	'required',
			'price'			=>	'required|numeric',
			'margin'		=>	'required|numeric',
			'box'			=>	'numeric',
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('products.new')->withErrors($validation);
	    }

		$product = new Product;
		$product->tenant_id = Auth::user()->tenant_id;
		$product->name = Input::get('name');
		//$product->category_id = Input::get('category_id');
		$product->slug = Str::slug(Input::get('name'));
		$product->description = Input::get('description');
		$product->price = Input::get('price');
		$product->margin = Input::get('margin');
		$product->box = Input::get('box');

		$category = Category::find(Input::get('category_id'));
		$product = $category->products()->insert($product);
		// TODO: add validation
		return Redirect::route('products.focus', array($product->id))
				->with('notice', 'Produto criado com sucesso');
	}

	public function getEdit($id)
	{
		$product = Product::find($id);
		if ($product == NULL)
		{
			return Response::error('404');
		}
		$view = View::make('products.edit');
		$view->product = $product;

		$categories = Category::all();
		$category_list = array();
		foreach ($categories as $category)
		{
			$category_list[$category->id] = $category->name;
		}
		$view->categories = $category_list;

		return $view;
	}

	public function postEdit($id)
	{
		// Process post of new product in MODEL

		$rules = array(
			'name'	=>	'required',
			'description'	=>	'required',
			'price'			=>	'required|numeric',
			'margin'		=>	'required|numeric',
			'box'			=>	'numeric',
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('products.edit', array($id))->withErrors($validation);
	    }

		$product = Product::find($id);
		$product->name = Input::get('name');
		$product->category_id = Input::get('category_id');
		$product->slug = Str::slug(Input::get('name'));
		$product->description = Input::get('description');
		$product->price = Input::get('price');
		$product->margin = Input::get('margin');
		$product->box = Input::get('box');
		$product->save();
		//$category->products()->save($product);
		// TODO: add validation
		return Redirect::route('products.focus', array($product->id))
				->with('notice', 'Produto atualizado com sucesso');
	}

	public function getDelete($id)
	{
		$product = Product::find($id);
		$product->is_alive = False;
		$product->save();
		return Redirect::route('products.index')
				->with('notice', 'Produto deletado com sucesso');
	}
}