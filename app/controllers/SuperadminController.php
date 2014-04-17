<?php

class SuperadminController extends BaseController
{
	public $restful = true;

	public function getChooseProfile()
	{
		$superadmin_email = Auth::user()->email;
		$cds = Tenant::where('email','=', $superadmin_email)->get();
		$view = View::make('superadmin.choose');
		$view->cds = $cds;

		return $view;
	}

	public function getSwitchProfile($id)
	{
		$tenant = Tenant::findOrFail($id);
		if ($tenant->email != Auth::user()->email) {
			// Uh oh, some smart ass is trying to hack me
			exit('Você não tem permissão para visualizar esse recurso');
		} else {
			// All clear
			$user = User::findOrFail(Auth::user()->id);
			$user->tenant_id = $id;
			$user->save();
		}
		return Redirect::route('snapshots.stock')->with('notice','Tudo ok! Logado como ' . $tenant->account_name);
	}
}