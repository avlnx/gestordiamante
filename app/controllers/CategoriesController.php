<?php

class CategoriesController extends BaseController
{
	public $restful = true;

	public function getIndex()
	{

		$view = View::make('categories.index');
		$view->categories = Category::all();

		return $view;
	}

	public function getNew()
	{
		$view = View::make('categories.new');
		return $view;
	}

	public function getEdit($id)
	{
		$category = Category::find($id);
		$view = View::make('categories.edit');
		$view->category = $category;
		return $view;
	}

	public function getDelete($id)
	{
		$category = Category::find($id);
		$category->is_alive = False;
		$category->save();

		return Redirect::route('categories.index')
				->with('notice', 'Categoria deletada com sucesso!');
	}

	public function postNew()
	{
		// Process post of new categoria in MODEL

		$rules = array(
			'name'	=>	'required',
			'description'	=>	'required'
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('categories.new')->withErrors($validation);
	    }

		$category = new Category;
		$category->tenant_id = Auth::user()->tenant_id;
		$category->name = Input::get('name');
		$category->slug = Str::slug(Input::get('name'));
		$category->description = Input::get('description');

		$category->save();

		return Redirect::route('categories.focus', array($category->id))
				->with('notice', 'Categoria criada com sucesso');
	}

	public function postEdit($id)
	{
		// Process post of new categoria in MODEL

		$rules = array(
			'name'	=>	'required',
			'description'	=>	'required'
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('categories.edit', array($id))->withErrors($validation);
	    }

		$category = Category::find($id);
		$category->name = Input::get('name');
		$category->slug = Str::slug(Input::get('name'));
		$category->description = Input::get('description');

		$category->save();

		return Redirect::route('categories.focus', array($category->id))
				->with('notice', 'Categoria atualizada com sucesso');
	}

	public function getFocus($id)
	{
		$category = Category::find($id);
		$num_products = Product::where('category_id', '=', $id)->count();

		$view = View::make('categories.focus');
		$view->category = $category;
		$view->num_products = $num_products;

		return $view;
	}

}