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
      $sales = Sale::where('tenant_id', '=', Auth::user()->tenant_id)
         ->orderBy('created_at', 'asc')
         ->limit(1000)
         ->get();

      // Return an array containing for period specified
      // Date: total_value
      
      // grouped by date
      return Response::json($sales); 
   }

}