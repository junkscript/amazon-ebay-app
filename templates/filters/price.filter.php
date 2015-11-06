<?php if (!empty($filter->resultAll)): ?>
	<h2><small>&nbsp;Price</small></h2>
	<div id="price-range">
		<div class="form-group">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<input type="text" id="amount" min="100" readonly style="border:0; color:#f6931f; font-weight:bold;">
						<div id="slider-range"></div>
					</div>
				</div>
	    	<div class="col-md-4 col-sm-4 col-xs-4">
	    		<div class="form-group">
			    	<input type="number" name="min-price" id="min-price" readonly class="input-sm form-control" value="<?= isset($_GET['min-price']) ? $_GET['min-price'] : $filter->getMinPrice($filter->resultAll) ?>">
		    	</div>
		  	</div>
		  	<div class="col-md-3 col-sm-3 col-xs-3 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
		  		To <?= setSymbolFromCyrrencyId($filter->resultAll[0]['priceId']); ?>
		  	</div>
	  		<div class="col-md-4 col-sm-4 col-xs-4">
	  			<div class="form-group">
		  	  	<input type="number" name="max-price" id="max-price" readonly class="input-sm form-control" value="<?= isset($_GET['max-price']) ? $_GET['max-price'] : $filter->getMaxPrice($filter->resultAll) ?>">
	  	  	</div>
	  		</div>
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
					</div>
			  </div>
			</div>
		</div>
	</div>
<?php endif; ?>