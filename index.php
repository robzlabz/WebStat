<?php 

$listDomain = explode("\n", file_get_contents('listdomain.txt'));
$domain = $listDomain[0];

// var_dump($listDomain);

// melihat grafik


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>WebStat 2</title>
 	<meta name="description" content="WebStat 2 with C3 Graph By RobzLabz Software Technology"> 	

 	<!-- jquery -->
 	<script src="//code.jquery.com/jquery.js"></script>
 	
 	<!-- Bootstrap -->
 	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

 	<!-- graph -->
 	<link rel="stylesheet" type="text/css" href="asset/css/c3.css">
 	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 	<script src="asset/js/c3.min.js"></script>
 </head>
 <body>
 	<div class="container">
		<h3 id="namadomain"><?php echo $domain ?></h3>
		<div id="chart" data-graph="csv.php?d=<?php echo base64_encode($domain) ?>">Loading Data...</div> 
 		<?php foreach ($listDomain as $domain): ?>
 			<button class="change btn btn-primary" data-csv="csv.php?d=<?php echo base64_encode($domain) ?>"><?php echo $domain; ?></button>
 		<?php endforeach ?> 		
 	</div>

<script type="text/javascript">
	$(function(){		

		var chart = c3.generate({
			bindto : '#chart',
			point: {
                    show: !1
            },
			data : { 
				x : 'x',					
				url : $('#chart').attr('data-graph'),	
				mimeType: 'json',
				type : 'line'				
			},
			axis : {
				x : {
					type : 'timeseries',
					tick : {
						format : "%Y-%m-%d %H:%M:%S",
						count : 10,
						fit: !0,
					}
				},
				y: 	{
					min: 0,
					ticks: 5
                }
			},
			zoom : {enabled : true},
			subchart : {show : true}
		});	

		$('.change').click(function(e){
			e.preventDefault();

			chart.load({
				url : $(this).attr('data-csv'),
				mimeType: 'json'
			});

			$('#namadomain').html($(this).html());
		});
	});
</script>
</body>
</html>