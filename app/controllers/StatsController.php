<?php

class StatsController extends BaseController
{
   public $restful = true;

   public function getIndex()
   {
      $view = View::make('stats.index');

      return $view;
   }

}