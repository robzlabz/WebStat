<?php 

if(isset($_GET['d'])) {
	$domain = trim(base64_decode($_GET['d']));	
	$domain = unserialize(file_get_contents("result/" . $domain));

	echo json_encode($domain);	
}


 ?>