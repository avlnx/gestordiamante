@extends('layouts.default')


@section('content')

<h1>Estatísticas</h1>   

<p class="lead">Visualize como anda a saúde do seu CD</p>

<div class='row'>
   <div id='vendas-stats'></div>
</div>


@stop

@section('scripts')

$.getJSON('stats/ajax.json/sales', function(data){
   clean_data = {};
   $.each(data, function(index,sale) {
      exploded_date = $.parseJSON(sale.exploded_date);
      date = Date.UTC(exploded_date.year,exploded_date.utc_month,exploded_date.day);
      // console.debug(date);
      total_for_sale = sale.total_value;
      if (clean_data[date]) {
         // date exists add total
         clean_data[date] += total_for_sale;
      } else {
         // date doesnt exist yet, create a new node
         clean_data[date] = total_for_sale;
      }
   });
   //console.debug(clean_data);
   final_data = [];
   $.each(clean_data, function(key,value){
      final_data.push([parseInt(key),parseFloat(value.toFixed(2))])
   });
   //console.debug(final_data);

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
      title: {text: '{{ Auth::user()->tenant->account_name }}'},
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
      series: [{
         name: 'Vendas',
         //pointInterval: 24 * 3600 * 1000,
         //pointStart: Date.UTC(2014, 6, 14),
         //data: [['1',12313],['2',32323],['3',43424]]
         data:  final_data
      }]
   });
   /*
   var chart = new Highcharts.Chart({

      chart: {
        renderTo: 'container'
      },
      xAxis: {
        type: 'datetime'
      },
      series: [{
        data: [
            [Date.UTC(2010, 0, 1), 29.9],
            [Date.UTC(2010, 0, 2), 71.5],
            [Date.UTC(2010, 0, 3), 106.4],
            [Date.UTC(2010, 0, 6), 129.2],
            [Date.UTC(2010, 0, 7), 144.0],
            [Date.UTC(2010, 0, 8), 176.0]
         ]
      }]

   });
   */
})


@stop