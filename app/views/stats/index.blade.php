@extends('layouts.default')


@section('content')

<h1>Estatísticas</h1>   

<p class="lead">Visualize como anda a saúde do seu CD</p>

<div class='row'>
   <div id='vendas-stats'></div>
</div>


@stop

@section('scripts')

// Disable buttons/show loader
update_loader('loading');

$.getJSON('stats/ajax.json/sales', function(data){
   /*
   tenants_data = {};
   //console.log(data);
   // Build tenants lists
   $.each(data, function(index,sale) {
      console.log(sale.tenant_id);
      if(tenants_data[sale.tenant_id]) {
         // tenant listed already, add new sale to it's list
      } else {
         // new tenant, 
      }
      tenant_id = $.parseJSON(sale.tenant_id);

   });
   */
   clean_data = {};
   $.each(data, function(index,sale) {
      exploded_date = $.parseJSON(sale.exploded_date);
      date = Date.UTC(exploded_date.year,exploded_date.utc_month,exploded_date.day);
      // console.debug(date);
      total_for_sale = sale.total_value;
      // Create a new node for the tenant if this is the first sale
      if(!clean_data[sale.tenant_name]) {
         // tenant doesnt exist, create it
         clean_data[sale.tenant_name] = {};
      }

      // Create a new node for the date if this is the first sale for the day
      if (clean_data[sale.tenant_name][date]) {
         // date exists add total
         clean_data[sale.tenant_name][date] += total_for_sale;
      } else {
         // date doesnt exist yet, create a new node
         clean_data[sale.tenant_name][date] = total_for_sale;
      }
      //clean_data[sale.tenant_id]['tenant_name'] = sale.tenant_name;

   });
   //console.debug(clean_data);
   /*
   # clean_data structure:
   Object { 
      10 = {
         12313213123 = 118.5, 
         1324234234324 = 39.5
      }, 
      15 = {
         43242342342 = 890.3, 
         4324242434 = 100.4
      } 
   }
   */
   /*
   # wanted structure:
   [
      {
         name:    10,
         data:    [
            [12313213123, 118.5],
            [1324234234324, 9.5]
         ]
      },
      {
         name:    15,
         data:    [
            [43242342342, 890.3],
            [4324242434, 100.4]
         ]
      }
   ]
   */
   bucket = [];
   $.each(clean_data, function(tenant_id,data_object) {
      var tuples = [];
      $.each(data_object, function(datenum, total) {
         tuples.push([parseInt(datenum),total]);
      });
      bucket.push(
         {
            name:   tenant_id,
            data:   tuples
         }
      );
   });
   //console.debug(bucket);

   Highcharts.setOptions({
      lang: {
         months: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
         weekdays: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
         decimalPoint: ',',
         loading: 'Carregando...',
         noData: 'Sem dados...',
         shortMonths: [ "Jan" , "Fev" , "Mar" , "Abr" , "Mai" , "Jun" , "Jul" , "Ago" , "Set" , "Out" , "Nov" , "Dez"],
         thousandsSep: '.',

      }
   });

   $('#vendas-stats').highcharts({
      chart: {
         type: 'line'
      },
      title: null,
      xAxis: {
         type: 'datetime'
      },
      yAxis: {
         title: {
             text: null
         }
      },
      tooltip: {
         //valuePrefix: 'R$',
         xDateFormat: '%d/%m/%Y',
         pointFormat: '<b>R${point.y}</b>'
      },
      /*
      series: [{
         name: 'Vendas',
         //pointInterval: 24 * 3600 * 1000,
         //pointStart: Date.UTC(2014, 6, 14),
         //data: [['1',12313],['2',32323],['3',43424]]
         data:  final_data    // [[4342432424234, 1434.34]]   Array of [Date.UTC, Float]
      }]
      */
      series:  bucket
   });
}).done(function(){
   update_loader('done');
});


@stop