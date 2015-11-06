<?php
		if (isset($_GET['search']) && !empty($_GET['search'])) {
		$per_page = count($result);
		if ($per_page) {
			if (isset($_GET['store'])) {
				if ($_GET['store'] == 'amazon')
					$num_pages = $filter->amazonPages;
				else if ($_GET['store'] == 'ebay')
					$num_pages = $filter->ebayPages;
				else 
					$num_pages = max(isset($filter->ebayPages) ? $filter->ebayPages : 1, isset($filter->amazonPages) ? $filter->amazonPages : 1);
			} else {
				$num_pages = max(isset($filter->ebayPages) ? $filter->ebayPages : 1, isset($filter->amazonPages) ? $filter->amazonPages : 1);
			}
			$page = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
			$limit = 2;

			if ($page >= 1) {
				echo '<li><a aria-label="first" href="' . $filter->buildUrl('page', 1) . '">first</a></li>';
				echo '<li><a aria-label="previous" href="' . $filter->buildUrl('page', $page ) . '">previous</a></li>';
			}

			$th = $page + 1;
			$start = $th - $limit;
			$end = $th + $limit;

			for ($j = 1; $j <= $num_pages; $j++) {
				if ($j >= $start && $j <= $end) {
					if ($j == ($page + 1)) {
						echo '<li class="active"><a href="' . $filter->buildUrl('page', $j) . '">' . $j . '</a></li>';
					} else {
					 	echo '<li><a href="' . $filter->buildUrl('page', $j) . '">' . $j . '</a></li>';
					}
				}
			}
			if ($j > $page && ($page + 2) < $j) {

				echo '<li><a href="' . $filter->buildUrl('page', $page + 2) . '"> next </a></li>';
				echo '<li><a href="' . $filter->buildUrl('page', $j - 1) . '"> last </a></li>';
			}
		}
	}
?>