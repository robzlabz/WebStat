<?php 

if(isset($_GET['d'])) {
	$domain = trim(base64_decode($_GET['d']));	
	$domain = unserialize(file_get_contents("result/" . $domain));

	if(count($domain['x'])-1 > (30*24)) {
		foreach($domain as $key => $dump) {		
			$max = count($domain[$key])-1;
			$domain[$key] = array_slice($domain[$key], $max-(30*24), $max);
		}	
	}

	echo json_encode($domain);	
}


 ?>