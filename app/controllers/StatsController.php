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

   public function getSales()
   {
      $view = View::make('stats.sales');  
      $superadmin_email = Auth::user()->email;
      $cds = Tenant::where('email','=', $superadmin_email)->get();
      $view->cds = $cds;
      return $view;
   }

   public function getSalesJson()
   {
      // Get all sale objects for superadmin or for admin
      
      if (Auth::user()->is_superadmin) {
         // superadmin
         $tenants_list = Tenant::where('email','=',Auth::user()->email)->lists('id');
      } else {
         // only admin
         $tenants_list = array(Auth::user()->tenant_id);
      }

      $sales = DB::table('sales')
         //->select(DB::raw('MONTH(created_at) as m, YEAR(created_at) as y, SUM(debit,credit,cash,deposit,bonus) as t'))
         ->select(DB::raw('tenant_id, DAY(created_at) as day, MONTH(created_at) as month, YEAR(created_at) as year, UNIX_TIMESTAMP(created_at)*1000 as timestamp, SUM(debit+credit+cash+deposit+bonus) as total'))
         ->whereRaw('created_at > DATE_SUB(now(), INTERVAL 30 DAY)')
         ->whereRaw('is_alive = 1')
         ->whereIn('tenant_id', $tenants_list)
         ->groupBy(DB::raw('tenant_id, YEAR(created_at), MONTH(created_at), DAY(created_at)'))
         ->orderBy('created_at', 'asc')
         ->get();

      // Create stats array
      $stats = array();

      // Create an array for each tenant
      foreach ($tenants_list as $tenant_id) {
         $stats[$tenant_id] = array();
         $stats[$tenant_id]['name'] = Tenant::find($tenant_id)->account_name;
         $stats[$tenant_id]['data']  = array();
      }

      foreach ($sales as $sale) {
         $tenant_id = $sale->tenant_id;
         $timestamp = (int)$sale->timestamp;
         $total = (float)$sale->total;
         array_push($stats[$tenant_id]['data'], [$timestamp,$total]);
      }

      $clean_stats = array();
      // clear tenants ids from indexes
      foreach ($stats as $row) {
         array_push($clean_stats, $row);
      }
      
      return Response::json($clean_stats); 
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