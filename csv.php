<?php 

if(isset($_GET['d'])) {
	$domain = base64_decode($_GET['d']);
	$domain = trim($domain);
	$domain = unserialize(file_get_contents("result/" . $domain));

	echo json_encode($domain);
	// die();

	
	// for ($i=0; $i < 50; $i++) { 
	// 	$time = mktime(0, 0+$i, 0, date("m")  , date("d")+$i, date("Y"));
	// 	$result["x"][] = $time*1000;
	// 	$result["Search"][] = rand(300,7000);
	// 	$result["Image"][] = rand(300,7000);
	// 	$result["Alexa"][] = rand(300,7000);
	// 	$result["Posting"][] = rand(300,7000);
	// }

	// echo json_encode($result);
}


 ?>