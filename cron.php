<?php 

date_default_timezone_set("Asia/Jakarta"); 
set_time_limit(0);

$listDomain = explode("\n", file_get_contents(__DIR__.'/listdomain.txt'));
$listDomain = array_map('trim', $listDomain);

foreach ($listDomain as $key => $domain) {
	if(empty($domain) || is_null($domain)) continue;
	$thread[$key] = popen("php check.php $domain", 'r');
}

foreach ($listDomain as $key => $domain) {
	if(empty($domain) || is_null($domain)) continue;
	$stream[$key] = stream_get_contents($thread[$key]);
	echo "Domain : $domain \n" . $stream[$key];
}

?>
