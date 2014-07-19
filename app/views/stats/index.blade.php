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

$.getJSON('stats/ajax.json/sales-fast', function(data){
   /*
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

   });
   //console.debug(clean_data);
   */
   /*
   # wanted structure:
   [
      {
         name:    'CD Joinville',
         data:    [
            [12313213123, 118.5],
            [1324234234324, 9.5]
         ]
      },
      {
         name:    'CD Curitiba',
         data:    [
            [43242342342, 890.3],
            [4324242434, 100.4]
         ]
      }
   ]
   */
   /*
   bucket = [];
   $.each(clean_data, function(tenant_id,data_object) {
      var tuples = [];
      $.each(data_object, function(datenum, total) {
         tuples.push([parseInt(datenum),parseFloat(total.toFixed(2))]);
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
      series:  bucket
   });
   */
   console.debug(data);
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
         xDateFormat: '%d/%m/%Y',
         pointFormat: '<em>{series.name}</em><br/><b>R${point.y}</b>'
      },
      series:  data
   });
}).done(function(){
   update_loader('done');
});


@stop