@extends('layouts.default')


@section('content')

<h1>Estatísticas - Vendas</h1>   

<p class="lead">Visualize como anda a saúde do seu CD</p>

<div class='row'>
   <div id='vendas-stats'></div>
</div>


@stop

@section('scripts')

// Disable buttons/show loader
update_loader('loading');

$.getJSON('ajax.json/sales', function(data){

   //console.debug(clean_data);
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
   /*
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
   console.log(data);
}).done(function(){
   update_loader('done');
});


@stop