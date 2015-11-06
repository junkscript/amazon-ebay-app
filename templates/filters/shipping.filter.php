<?php if (!empty($filter->resultAll)): ?>
	<?php if (count($filter->getShipping($filter->resultAll)) > 1): ?>
		<h2><small>&nbsp;Shipping</small></h3>
		<div id="brand">
			<div class="form-group">
				<?php foreach($filter->getShipping($filter->resultAll) as $key => $value): ?>
					<a href="<?= $filter->buildUrl('shipping', $key) ?>"><?= $value; ?></a><br>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>