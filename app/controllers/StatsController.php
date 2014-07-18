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
      // Get all sale objects for superadmin or for admin
      if (Auth::user()->is_superadmin) {
         // superadmin
         $tenants_list = Tenant::where('email','=',Auth::user()->email)->lists('id');
         $sales = Sale::whereIn('tenant_id', $tenants_list)
                  ->orderBy('created_at','asc')
                  //->groupBy('tenant_id')  Breaks code
                  ->limit(1000)
                  ->get();
      } else {
         // only admin
         $sales = Sale::where('tenant_id', '=', Auth::user()->tenant_id)
         ->orderBy('created_at', 'asc')
         ->limit(1000)
         ->get();
      }
      
      // grouped by date
      return Response::json($sales); 
   }

}