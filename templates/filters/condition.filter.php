<?php if (!empty($filter->resultAll)): ?>
	<h2><small>&nbsp;Condition</small></h2>
	<div id="condition">
		<div class="form-group">
			<?php foreach($filter->getCondition($filter->resultAll) as $key => $value): ?>
				<a href="<?= $filter->buildUrl('condition', $key) ?>"><?= $value; ?></a><br>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>