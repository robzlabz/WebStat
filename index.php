<?php 

$listDomain = explode("\n", file_get_contents('listdomain.txt'));
$listDomain = array_map('trim', $listDomain);
$domain = $listDomain[0];

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>WebStat 2</title>
 	<meta name="description" content="WebStat 2 with C3 Graph By RobzLabz Software Technology"> 	
 	<script src="//code.jquery.com/jquery.js"></script>
 	<link rel="stylesheet" href="asset/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="asset/css/c3.css">
 	<link rel="stylesheet" type="text/css" href="asset/css/style.css">
 	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 	<script src="asset/js/c3.min.js"></script>
 </head>
 <body>
 	<div class="container">
		<h3 id="namadomain"><?php echo $domain; ?></h3>
		<div id="chart" data-graph="csv.php?d=<?php echo base64_encode($domain); ?>">Loading Data...</div> 
 		<?php foreach ($listDomain as $domain): ?>
 			<?php if (! empty($domain)): ?> 				
 				<button class="change btn btn-primary" data-csv="csv.php?d=<?php echo base64_encode($domain); ?>"><?php echo $domain; ?></button>
 			<?php endif; ?> 				
 		<?php endforeach; ?>  		 		
 	</div>

	<hr><br>
	<div class="container">
 		<button id="run" class="btn btn-success">Run Cron</button>
 	
 		<hr>

	 	<?php foreach ($listDomain as $key => $domain): ?>
	 		<?php 
	 			$data = file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/csv.php?d=" . base64_encode($domain));

	 			$data = json_decode($data);

	 			if(empty($data)) continue;
	 			
	 			if(end($data->Search) == 0) : 
	 		?>
			<p class="warning"><span class="atention">!</span> <?php echo $domain ?> </p>
	 		<?php endif; ?>		
	 	<?php endforeach ?>
 	</div>

	<script type="text/javascript">
		$(function(){		

			$('#run').click(function(e){
				e.preventDefault();

				$('#run').html('Please wait...');
				$('#run').prop('disabled',true);

				$.get("cron.php")
					.always(function(){
						alert("Success");
						location.reload();
					});


			})

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
				subchart : {show : false}
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
