<?php   
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
	header('Content-Type: text/html; charset=utf-8'); 
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
		<title>Amazon-Ebay</title>
	</head>
	<body>
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<form action="" method="get" id="form">
							<div class="form-group input-group">
	              <input type="text" class="form-control" name="search" id="tags" value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Search...">
	              <span class="input-group-btn">
				        	<button type="submit" class="btn btn-default" type="button" id="button"><i class="fa fa-search"></i></button>
	              </span>
	            </div>
						</form>
					</div>		
				</div>
		</div>
		<div class="container">
			<?php 
				$resultAll = [];
				if (isset($_GET['search']) && !empty($_GET['search'])) {
					$findSearch = str_replace(" ", "+", $_GET['search']);
					require 'ebay.php';
					require 'amazon.php';

      		if (!empty($resultEbay) && !empty($resultAmazon))
      			$resultAll = array_merge($resultEbay, $resultAmazon);
      		if (empty($resultAmazon))
      			$resultAll = $resultEbay;
      		else if (empty($resultEbay))
      			$resultAll = $resultAmazon;
	      	shuffle($resultAll);
	      	if(empty($resultAll)) {
	      		echo '<h1 class="text-center"><small>No search result!!!</small></h1>';
	      	}
				}
			?>
			<!-- Content -->
			<div class="container">
		    <div class="row">
					<?php if (isset($_GET['search']) && !empty($_GET['search'])) :?>
			      <?php foreach($resultAll as $key => $value) : ?>
			      	<div class="col-md-2 col-sm-4 col-xs-6">
			          <div class="product">
			          	<?php if ($value['shopName'] === 'at eBay') { ?>
			          		<img src="<?=$value['galleryURL']; ?>" height=96 class="text-center">
			            	<div class="title" class="small">
			              	<a href="goto.php/'<?= base64_encode($value['viewItemURL']);?>'" target="_blank">
			                	<?= $value['title']; ?>
			              	</a>
			            	</div>
			            	<div class="price">
			              	<?= $value['sellingStatus']['currentPrice']['currencyId'] . ' ' . $value['sellingStatus']['currentPrice']['value']; ?>
			            	</div>
			          	<?php } else { ?>
			          		<img src="<?=$value['MediumImage']; ?>" height=96 class="text-center">
		              	<div class="title">
		                	<a href="/goto.php/'<?= base64_encode($value['DetailPageURL']);?>'" target="_blank">
		                  	<?= $value['ItemAttributes']['Title']; ?>
			                </a>
		              	</div>
		              	<div class="price">
		                	<?php 
		                  	$listPrice       = isset($value['ItemAttributes']['ListPrice']['FormattedPrice']) ? $value['ItemAttributes']['ListPrice']['FormattedPrice'] : 0;
		                  	$lowestNewPrice  = isset($value['OfferSummary']['LowestNewPrice']['FormattedPrice']) ? $value['OfferSummary']['LowestNewPrice']['FormattedPrice'] : 0;
		                  	$lowestUsedPrice = isset($value['OfferSummary']['LowestUsedPrice']['FormattedPrice']) ? $value['OfferSummary']['LowestUsedPrice']['FormattedPrice'] : 0;
		                  	$zero = (float)0;

		                  	$lP_num  = number_format((float)substr($listPrice, 2, strlen($listPrice)), 2, '.', ',');
		                  	$lNP_num = number_format((float)substr($lowestNewPrice, 2, strlen($lowestNewPrice)), 2, '.', ',');
		                  	$lUP_num = number_format((float)substr($lowestUsedPrice, 2, strlen($lowestUsedPrice)), 2, '.', ',');

		                  	if ($lP_num > $zero && $lNP_num > $zero) {
		                  		if ($lNP_num < $lP_num)
		                  			echo '<del>' . substr($listPrice, 0, 2) . $lP_num . '</del>' . ' '. substr($lowestNewPrice, 0, 2) . $lNP_num;
		                  		if ($lNP_num >= $lP_num)
		                  			echo substr($listPrice, 0, 2) . $lP_num;
		                  	}
	                  		if ($lP_num == $zero && $lNP_num == $zero && $lUP_num > $zero) {
	                  			echo substr($lowestUsedPrice, 0, 2) . $lUP_num;
	                  		}
	                  		if ($lP_num == $zero && $lNP_num > $zero && $lUP_num == $zero) {
	                  			echo substr($lowestNewPrice, 0, 2) . $lNP_num;
	                  		}
	                  		if ($lP_num > $zero && $lNP_num == $zero && $lUP_num == $zero) {
	                  			echo substr($listPrice, 0, 2) . $lP_num;
	                  		}
	                  		if ($lP_num == $zero && $lNP_num == $zero && $lUP_num == $zero) {
	                  			echo substr($listPrice, 0, 2) . $lP_num;
	                  		}
		                	?>
		              	</div>
			          	<?php } ?>
			          	<h5><i><small><?= $value['shopName'];?></small></i></h5>
			          </div>
		          </div>
			    	<?php endforeach;?>    
		      <?php endif; ?>
		    </div>
		  </div>
		  <!-- End content -->
		  <!-- Pagination -->
		  <div class="container">
		  	<div class="row">
		  		<div class="pull-right">
		  			<nav>
			  			<ul class="pagination">
					  		<?php
						  		$per_page = count($resultAll);
						  		if ($per_page) {
							  		$num_pages = max(isset($totalEbayPages) ? $totalEbayPages : 1, isset($totalAmazonPages) ? $totalAmazonPages : 1);
							  		$page = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
							  		$limit = 2;
						
										if ($page >= 1) {
											echo '<li><a aria-label="first" href="?search=' . str_replace(" ", "+", $_GET['search']) . '&page=1">first</a></li>';
											echo '<li><a aria-label="previous" href="?search=' . str_replace(" ", "+", $_GET['search']) . '&page=' . $page . '">previous</a></li>';
										}

										$th = $page + 1;
										$start = $th - $limit;
										$end = $th + $limit;

										for ($j = 1; $j <= $num_pages; $j++) {
											if ($j >= $start && $j <= $end) {
												if ($j == ($page + 1)) {
													echo '<li class="active"><a href="?search=' . $_GET['search'] . '&page=' . $j . '">' . $j . '</a></li>';
												} else {
														echo '<li><a href="?search=' . $_GET['search'] . '&page=' . $j . '">' . $j . '</a></li>';
												}
											}
										}
										if ($j > $page && ($page + 2) < $j) {
											echo '<li><a href="?search=' . $_GET['search'] . '&page=' . ($page + 2) . '"> next </a></li>';
											echo '<li><a href="?search=' . $_GET['search'] . '&page=' . ($j - 1) . '"> last </a></li>';
										}
									}
								?>
							</ul>
						</nav>
		  		</div>
		  	</div>
		  </div>
		<!-- end Pagination-->
		</div>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>