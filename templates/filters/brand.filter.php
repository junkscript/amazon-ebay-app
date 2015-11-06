<?php if (!empty($filter->resultAll)): ?>
	<?php if (count($filter->getBrands($filter->resultAll)) > 1): ?>
		<h2><small>&nbsp;Brand</small></h2>
		<div id="brand">
			<div class="form-group">
				<?php foreach($filter->getBrands($filter->resultAll) as $key => $value): ?>
					<a href="<?= $filter->buildUrl('brand', $key) ?>"><?= $value; ?></a><br>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>