<?php

class StatsController extends BaseController
{
   public $restful = true;

   public function getIndex()
   {

      $view = View::make('stats.index');
      $superadmin_email = Auth::user()->email;
      $cds = Tenant::where('email','=', $superadmin_email)->get();
      $view->cds = $cds;
      return $view;
   }

}