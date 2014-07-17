@extends('layouts.default')


@section('content')

<h1>Estatísticas</h1>   

<p class="lead">Visualize como anda a saúde do seu CD</p>

<div class="row">
   <div class="span6">
      <h6>Vendas</h6>
      <div id='vendas-stats'></div>
   </div>
   <div class="span6">
      <h6>Estoque</h6>
   </div>
</div>

<script type="text/javascript">

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

   // Create the data table.
   var sales = [['Dia','Vendas']];

   $.getJSON('stats/ajax.json/sales', function(data) {
      // var sales = [['Dia','Vendas']];
      $.each(data, function(i,sale){
         sales.push([sale.day,parseFloat(sale.total_value)]);
      });
   });
   console.debug(sales);

   var data = google.visualization.arrayToDataTable(sales);


   // Set chart options
   var options = {'title':'Suas vendas nos últimos 30 dias', hAxis: {showTextEvery: 6}};

   // Instantiate and draw our chart, passing in some options.
   var chart = new google.visualization.LineChart(document.getElementById('vendas-stats'));
   chart.draw(data, options);
}
</script>

@stop

