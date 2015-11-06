<?php 
	/**
	* Filter all result from ebay and amazon Finding API
	*/
	class Filter {
		// Construct
		function __construct() {
			require_once $_SERVER['DOCUMENT_ROOT'] . '/ebay.php';
			require_once $_SERVER['DOCUMENT_ROOT'] . '/amazon.php';
			if (!empty($resultEbay) && !empty($resultAmazon)) {
  			$this->resultAll = array_merge($resultEbay, $resultAmazon);
  			$this->store = array('all' => 'All', 'ebay' => 'eBay', 'amazon' => 'Amazon');
  			$this->ebayPages = $totalEbayPages;
  			$this->amazonPages = $totalAmazonPages;
  		}
  		if (empty($resultAmazon)) {
  			$this->resultAll = $resultEbay;
  			$this->store = array('all' => 'All', 'ebay' => 'eBay');
  			$this->ebayPages = $totalEbayPages;
  			$this->amazonPages = 0;
  		}
  		if (empty($resultEbay)) {
  			$this->resultAll = $resultAmazon;
  			$this->store = array('all' => 'All', 'amazon' => 'Amazon');
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
			if ($store == 'all') {
				return $array;
			} else {
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
		//Function to build url
		function buildUrl($itemName = '', $value) {
			$query = explode('?', $_SERVER['REQUEST_URI']); 
			parse_str($query[1], $data); 
			$data[$itemName] = $value; 

			return $query[0] . '?' . http_build_query($data); // rebuild URL
		}
		function removeFromUrl($itemName = '', $value) {
			$query = explode('?', $_SERVER['REQUEST_URI']); 
			parse_str($query[1], $data); 
			unset($data[$itemName]);
			//$data[$itemName] = $value; 

			return $query[0] . '?' . http_build_query($data); // rebuild URL
		}
		//Return all conditions
		function getCondition() {
			$conditions = [];
			foreach ($this->resultAll as $key => $value) {
				if (isset($value['condition'])) {
					$conditions[$value['condition']] = strtoupper(substr($value['condition'], 0, 1)) . substr($value['condition'], 1);
				}
			}
			$conditions['all'] = 'All';
			ksort($conditions);
			return $conditions;
		}
		//Sort result by condition
		function sortCondition($array, $condition = 'all') {
			$result = [];
			if ($condition == 'all') {
				return $array;
			} else {
				foreach ($array as $key => $value) {
					if (isset($value['condition'])) {
						if ($value['condition'] == $condition) {
							$result[$key] = $value;
						}
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
			return array_merge(array('all' => 'All'), $brands);
		}
		//Sort result by brand
		function sortBrand($array, $brand = 'all') {
			$result = [];
			if ($brand == 'all') {
				return $array;
			} else {
				foreach ($array as $key => $value) {
					if (isset($value['brand'])) {
						if (strtolower($value['brand']) == $brand) {
							$result[$key] = $value;
						}
					} else {
					}
				}
			}
			return $result;
		}
		//Get all shipping
		function getShipping($array) {
			$shipping = [];
			foreach ($array as $key => $value) {
				if (isset($value['shipping'])) {
					$shipping[strtolower($value['shipping'])] = strtoupper(substr($value['shipping'], 0, 1)) . substr($value['shipping'], 1);
				}
			}
			if (!empty($shipping)) ksort($shipping);
			return array_merge(array('all' => 'All'), $shipping);
		}
		//Sort result by shipping
		function sortShipping($array, $shipping = 'all') {
			$result = [];
			if ($shipping == 'all') {
				return $array;
			} else {
				foreach ($array as $key => $value) {
					if (isset($value['shipping'])) {
						if (strtolower($value['shipping']) == $shipping) {
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

	}
?>