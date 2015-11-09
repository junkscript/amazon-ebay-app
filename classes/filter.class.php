<?php 
	/**
	* Filter all result from ebay and amazon Finding API
	*/
	class Filter {
		// Construct
		function __construct() {
			require $_SERVER['DOCUMENT_ROOT'] . '/includes/ebay.php';
			require $_SERVER['DOCUMENT_ROOT'] . '/includes/amazon.php';
			if (!empty($resultEbay) && !empty($resultAmazon)) {
  			$this->resultAll = array_merge($resultEbay, $resultAmazon);
  			$this->store = array('ebay' => 'eBay', 'amazon' => 'Amazon');
  			$this->ebayPages = $totalEbayPages;
  			$this->amazonPages = $totalAmazonPages;
  		}
  		if (empty($resultAmazon)) {
  			$this->resultAll = $resultEbay;
  			$this->store = array('ebay' => 'eBay');
  			$this->ebayPages = $totalEbayPages;
  			$this->amazonPages = 0;
  		}
  		if (empty($resultEbay)) {
  			$this->resultAll = $resultAmazon;
  			$this->store = array('amazon' => 'Amazon');
  			$this->ebayPages = 0;
  			$this->amazonPages = $totalAmazonPages;
  		}
  		if (!empty($resultAmazonTopSellers)) $this->topSellers = $resultAmazonTopSellers;
  		shuffle($this->resultAll);
  		$this->totalResult = number_format(isset($totalAmazonResult) ? $totalAmazonResult : 0 + $totalEbayResult, 0, '.', ',');
		}
		// Sort result by Shop
		function sortShop($array, $store = 'all') {
			$shop = '';
			switch ($store) {
				case 'ebay':
					$shop = 'at eBay';
				break;
				case 'amazon':
					$shop = 'at Amazon';
				break;
			}
			$result = [];
			foreach ($array as $key => $value) {
				if ($value['shopName'] == $shop) {
					$result[$key] = $value;
				}
			}
			return $result;
		}
		function getMinPrice($array) {
			$min = (!empty($array)) ? $array[0]['currentPrice'] : 0;
			foreach ($array as $key => $value) {
				if ($min >= $value['currentPrice']) {
					$min = $value['currentPrice'];
				}
			}
			return floor($min);
		}
		function getMaxPrice($array) {
			$max = (!empty($array)) ? $array[0]['currentPrice'] : 0;
			foreach ($array as $key => $value) {
				if ($max <= $value['currentPrice']) {
					$max = $value['currentPrice'];
				}
			}
			return ceil($max);
		}
		//Sort result by price from min to max
		function sortPrice($array, $min = 0.00, $max = 10.00) {
			$result = [];
			foreach ($array as $key => $value) {
				if ($value['currentPrice'] >= $min && $value['currentPrice'] <= $max) {
					$result[$key] = $value;
				}
			}
			return $result;
		}
		//Return all conditions
		function getCondition($array) {
			$conditions = [];
			foreach ($array as $key => $value) {
				if (isset($value['condition'])) {
					$conditions[$value['condition']] = strtoupper(substr($value['condition'], 0, 1)) . substr($value['condition'], 1);
				}
			}
			if (!empty($conditions)) ksort($conditions);
			return $conditions;
		}
		//Sort result by condition
		function sortCondition($array, $condition = '') {
			$result = [];
			foreach ($array as $key => $value) {
				if (isset($value['condition'])) {
					if ($value['condition'] == $condition) {
						$result[$key] = $value;
					}
				}
			}
			return $result;
		}
		//Get all brands (Amazon)
		function getBrands($array) {
			$brands = [];
			foreach ($array as $key => $value) {
				if (isset($value['brand'])) {
					$brands[strtolower($value['brand'])] = strtoupper(substr($value['brand'], 0, 1)) . strtolower(substr($value['brand'], 1));
				}
			}
			if (!empty($brands)) ksort($brands);
			return array_merge($brands);
		}
		//Sort result by brand
		function sortBrand($array, $brand = '') {
			$result = [];
			foreach ($array as $key => $value) {
				if (isset($value['brand'])) {
					if (strtolower($value['brand']) == $brand) {
						$result[$key] = $value;
					}
				} else {
				}
			}
			return $result;
		}
		//Get pricing
		function getPricing($array) {
			$pricing = [];
			foreach ($array as $key => $value) {
				if (isset($value['pricing'])) {
					$pricing[strtolower($value['pricing'])] = $value['pricing'];
				}
			}
			if (!empty($pricing)) ksort($pricing);
			return $pricing;
		}
		//Sort result by pricing
		function sortPricing($array, $pricing = 'all') {
			$result = [];
			if ($pricing == 'all') {
				return $array;
			} else {
				foreach ($array as $key => $value) {
					if (isset($value['pricing'])) {
						if (strtolower($value['pricing']) == $pricing) {
							$result[$key] = $value;
						}
					} else {
					}
				}
			}
			return $result;
		}
		//Debugging
		function debug($var) {
			echo '<pre>'; print_r($var); echo '</pre>';
		}
		//Add $_GET['paramentr] to url and replace it if exist
		function buildUrl($itemName = '', $value) {
			$query = explode('?', $_SERVER['REQUEST_URI']);
			parse_str($query[1], $data);
			$data[$itemName] = $value;
			return $query[0] . '?' . http_build_query($data); // rebuild URL
		}
		function getUrlParamterValue($url, $itemName) {
			$query = explode('?', $url);
			parse_str($query[1], $data);
			return $data[$itemName]; // rebuild URL
		}
		//Remove $_GET['paramentr] from url
		function removeFromUrl($itemName = '', $value) {
			$query = explode('?', $_SERVER['REQUEST_URI']);
			parse_str($query[1], $data);
			unset($data[$itemName]);
			//$data[$itemName] = $value;
			return $query[0] . '?' . http_build_query($data); // rebuild URL
		}

	}
?>