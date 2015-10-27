<?php 

	$jsonItems = [];

	$string = isset($_GET['tag']) ? $_GET['tag'] : "";
	$string = str_replace(" ", "+", $string);

	$options = array('http' => 
		array(
			'mathod' => 'GET',
			'timeout' => '1' // if can't load JSON
		)
	);
	$context = stream_context_create($options);
/**
 * Autocomplete Amazon
 */	
	$contentAmazon = file_get_contents("http://completion.amazon.co.uk/search/complete?method=completion&mkt=3&client=amazon-search-ui&x=String&search-alias=aps&q=" . $string . "&qs=&cf=1&noCacheIE=1445779583431&fb=1&sc=1&", false, $context);
/**
 * Autocomplete Ebay
 */	
	$contentEbay = file_get_contents("http://autosug.ebaystatic.com/autosug?kwd=" . $string . "&_jgr=1&sId=3&_ch=0&callback=nil", false, $context);

	if (!empty($string)) {
		if (isset($contentEbay)) {
			$stringEbaySearch = substr($contentEbay, strpos($contentEbay, "sug"), strlen($contentEbay) - strpos($contentEbay, "sug"));
			$posEbay_1 = strpos($stringEbaySearch, '[') + 1;
			$posEbay_2 = strpos($stringEbaySearch, ']');
			$stringEbay = substr($stringEbaySearch, $posEbay_1, $posEbay_2 - $posEbay_1);
			$stringEbay = str_replace("\"", "", $stringEbay);
			$itemsEbay = explode(',', $stringEbay);
		}
		
		if (isset($contentAmazon)) {
			$pos_1 = strpos($contentAmazon, '[');
			$pos_start = strpos($contentAmazon, '[', $pos_1 + 1);
			$tag = substr($contentAmazon, $pos_1 + 1, $pos_start - $pos_1 - 2);
			$pos_end = strpos($contentAmazon, ']');
			$items = substr($contentAmazon, $pos_start + 1, $pos_end - $pos_start - 1);
			$items = str_replace("\"", "", $items);
			$itemsAmazon = explode(',', $items);
		}

		/**
		 * Return array with Amazon and Ebay condition tag
		 */
		$jsonItems['tag'] = $string;
		if (empty($itemsEbay) || substr($itemsEbay[0], 0, 2) == '**') {
			$jsonItems['condition'] = $itemsAmazon;
		} else if (empty($itemsAmazon)) {
			$jsonItems['condition'] = $itemsEbay;
		} else if (empty($itemsEbay) && empty($itemsAmazon)) {
			$jsonItems['condition'] = [];
		} else {
			$jsonItems['condition'] = array_merge($itemsEbay, $itemsAmazon);
			$jsonItems['condition'] = array_unique($jsonItems['condition']);
		}
		if (count($jsonItems['condition']) > 10) {
			$jsonItems['condition'] = array_slice($jsonItems['condition'], 0, 10);
		}

		echo json_encode($jsonItems);
	}
?>