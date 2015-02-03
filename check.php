<?php 

	require_once(__DIR__.'/lib/simple_html_dom.php');

	$domain = $argv[1];

	if(empty($argv[2])) {
		$list 		= array('Search', 'Image', 'Alexa', 'Post');

		foreach ($list as $type) {
			$stream[$type] = popen("php check.php $domain $type", 'r');
		}

		# Load Cache
		$file = __DIR__."/result/{$domain}";
		if(file_exists($file)) {
			$result = unserialize(file_get_contents($file));
		} else {
			$result = array();
			$result["x"] = array();
			foreach ($list as $val) {
				$result[$val] = array();
			}
		}

		# Create Result
		$time = mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
		$result["x"][] = (float) $time*1000;
		foreach ($list as $key => $val) {
			$res = (int) stream_get_contents($stream[$list[$key]]);
			$result[$val][] = $res;
			echo " + " . ucwords($list[$key]) . " : " . $res . "\n";
		}

		# Save Result
		file_put_contents($file, serialize($result));
		$date = date('d-m-y h:i:s', $time);
	
		$timeExec = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
		echo " > Execution Time : $timeExec \n";
		echo " > Now Time : $date \n\n";
			  


	} else {
		switch ($argv[2]) {
			case 'Search':
				// google index crawl
				$html = @file_get_contents("https://www.google.com/search?q=site:{$domain}");		
				$obj = str_get_html($html);	
				$result = $obj->find('div[id=resultStats]',0)->plaintext;	
				preg_match("/[0-9,]+/", $result, $output);
				$web_search = str_replace(',', '', $output[0]);	
				echo trim($web_search);
				break;
			case 'Image':
				// google image crawl
				$html = @file_get_contents("https://www.google.com/search?q=site:{$domain}&tbm=isch");	
				$obj = str_get_html($html);	
				$result = $obj->find('div[id=resultStats]',0)->plaintext;
				preg_match("/[0-9,]+/", $result, $output);
				$image_search = empty($output) ? '0' : str_replace(',', '', $output[0]);
				echo trim($image_search);
				break;
			case 'Alexa' :
				// alexa
				$html = @file_get_contents("http://www.alexa.com/siteinfo/{$domain}");
				if(empty($html)) { die("Crawl Error"); }
				$obj = str_get_html($html);	
				$result = $obj->find('strong[class=metrics-data]',0)->plaintext;	
				preg_match("/[0-9,]+/", $result, $output);
				$alexa = empty($output) ? '0' : str_replace(',', '', $output[0]);
				echo trim($alexa);
				break;
			case 'Post':
				// Post Published	
				$post = file_get_contents("http://robbynr.com/tools/keyword/?url={$domain}&json=yes");
				$post = is_numeric($post) ? $post : 0;
				echo trim($post);
				break;
		}
	}

	

 ?>
