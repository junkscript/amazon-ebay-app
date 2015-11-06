<?php if (!empty($filter->resultAll)): ?>
	<h2><small>&nbsp;Store</small></h2>
	<div id="store">
		<div class="form-group">
			<?php foreach($filter->store as $key => $value): ?>	
				<a href="<?= $filter->buildUrl('store', $key) ?>"><?= $value; ?></a><br>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>