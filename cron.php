<?php 
set_time_limit(0);

require_once(__DIR__.'/lib/simple_html_dom.php');

$listDomain = explode("\n", file_get_contents(__DIR__.'/listdomain.txt'));


foreach ($listDomain as $domain) {

	if(empty($domain)) continue;

	$domain = trim($domain); // weired _ 

	// google index crawl
	$html = @file_get_contents("https://www.google.com/search?q=site:{$domain}");		
	$obj = str_get_html($html);	
	$result = $obj->find('div[id=resultStats]',0)->plaintext;	
	preg_match("/[0-9,]+/", $result, $output);
	$web_search = str_replace(',', '', $output[0]);	

	// google image crawl
	$html = @file_get_contents("https://www.google.com/search?q=site:{$domain}&tbm=isch");	
	$obj = str_get_html($html);	
	$result = $obj->find('div[id=resultStats]',0)->plaintext;
	preg_match("/[0-9,]+/", $result, $output);
	$image_search = empty($output) ? '0' : str_replace(',', '', $output[0]);

	// alexa
	$html = @file_get_contents("http://www.alexa.com/siteinfo/{$domain}");
	if(empty($html)) { die("Crawl Error"); }
	$obj = str_get_html($html);	
	$result = $obj->find('strong[class=metrics-data]',0)->plaintext;	
	preg_match("/[0-9,]+/", $result, $output);
	$alexa = empty($output) ? '0' : str_replace(',', '', $output[0]);
	
	// Post Published	
	$post = file_get_contents("http://robbynr.com/tools/keyword/?url={$domain}&json=yes");
	$post = is_numeric($post) ? $post : 0;
	
	$web_search = trim($web_search);
	$image_search = trim($image_search);
	$post = trim($post);
	$alexa = trim($alexa);

	// load last cache
	$file = "result/{$domain}";
	if(file_exists($file)) {
		$result = unserialize(file_get_contents($file));
	} else {
		$result = array();
		$result["x"] = array();
		$result["Search"] = array();
		$result["Image"] = array();
		$result["Alexa"] = array();
		$result["Posting"] = array();
	}

	// create result	
	$time = mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
	$result["x"][] = (float) $time*1000;
	$result["Search"][] = (int)$web_search;
	$result["Image"][] = (int)$image_search;
	$result["Alexa"][] = (int)$alexa;
	$result["Posting"][] = (int)$post;


	echo "Finishing $domain <br>";

	file_put_contents($file, serialize($result));
	sleep(5);
	// ini branch master
}


 ?>