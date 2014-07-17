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

   public function getSalesForPeriod()
   {
      // Get all sale objects for the period in question
      $sales = Sale::all();
      
      // grouped by date
      return Response::json($sales); 
   }

}