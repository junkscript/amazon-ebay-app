<?php   
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
	header('Content-Type: text/html; charset=utf-8'); 
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
		<title>Amazon-Ebay</title>
	</head>
	<body>
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3" id="">
						<form action="" method="get" id="form">
							<div class="form-group input-group">
	              <input type="text" class="form-control" name="search" id="tags" value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Search...">
	              <span class="input-group-btn">
				        	<button type="submit" class="btn btn-default" type="button" id="search-submit"><i class="fa fa-search"></i></button>
	              </span>
	            </div>
						</form>
					</div>		
				</div>
		</div>
		<div class="container-fluid">
			<?php
				$resultAll = [];
				if (isset($_GET['search']) && !empty($_GET['search'])) {
					$findSearch = str_replace(" ", "+", $_GET['search']);
	      	require 'classes/filter.class.php';
	      	$filter = new Filter();
	      	$result = $filter->resultAll;

	      	if (isset($_GET['min-price']) && isset($_GET['max-price'])) {
	      		$result = $filter->sortPrice($result, (float)$_GET['min-price'], (float)$_GET['max-price']);
	      	}
					if (isset($_GET['store'])) {
						$result = $filter->sortShop($result, $_GET['store']);
					}
	      	if (isset($_GET['condition'])) {
	      		$result = $filter->sortCondition($result, $_GET['condition']);
	      	}
	      	if(isset($_GET['brand'])) {
	      		$result = $filter->sortBrand($result, $_GET['brand']);
	      	}
					if (isset($_GET['pricing'])) {
						$result = $filter->sortPricing($result, $_GET['pricing']);
					}

	      	if(empty($filter->resultAll)) {
	      		echo '<h1 class="text-center"><small>No search result!!!</small></h1>';
	      	}
				}
			?>
			<!-- Content -->
		    <div class="row"> 
		    	<!-- Filters -->
					<?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
			    	<div class="col-md-2 col-sm-4 col-xs-12">
			    		<!-- Top Sellers-->
			      	<?php require 'templates/seller.tpl.php'; ?>
			    		<form  action='' method="get" id="form-price-filter" name="form_filter">

				    		<?php if (isset($_GET['search'])): ?>
									<input type="hidden" name="search" value="<?= $_GET['search'] ?>">
				    			<input type="hidden" id="min" value="<?= $filter->getMinPrice($filter->resultAll) ?>">
				    			<input type="hidden" id="max" value="<?= $filter->getMaxPrice($filter->resultAll) ?>">
		    					<input type="hidden" id="cur-min" value="<?= isset($_GET['min-price']) ? $_GET['min-price'] : $filter->getMinPrice($filter->resultAll) ?>">
				    			<input type="hidden" id="cur-max" value="<?= isset($_GET['max-price']) ? $_GET['max-price'] : $filter->getMaxPrice($filter->resultAll) ?>">
									<?php if (isset($_GET['page'])): ?>
										<input type="hidden" name="page" value="<?= $_GET['page'] ?>">
									<?php endif; ?>
				    		<?php endif; ?>

			    			<div id="filters">
				    			<!-- Price filter -->
				    			<?php require 'templates/filters/price.filter.php'; ?>
					    		<!-- Store filter-->
				    			<?php require 'templates/filters/store.filter.php'; ?>
				    			<!-- Condition filter -->
			    				<?php require 'templates/filters/condition.filter.php'; ?>
				    			<!-- Brand filter -->
				    			<?php require 'templates/filters/brand.filter.php'; ?>
									<!-- Pricing filter -->
									<?php require 'templates/filters/pricing.filter.php'; ?>
			    			</div>
			    		</form>
			    	</div>

		    	<div class="col-md-10 col-sm-8 col-xs-12">
		    		<!-- Products -->
			    	<?php require 'templates/products.tpl.php'; ?>
			    </div>
			    <?php endif; ?>
		  	</div>
		  </div>

		  <!-- Pagination -->
		  <div class="container">
		  	<div class="row">
		  		<div class="pull-right">
		  			<nav>
			  			<ul class="pagination">
					  		<?php require 'templates/pagination.tpl.php'; ?>
							</ul>
						</nav>
		  		</div>
		  	</div>
		  </div>
		</div> 
	 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>