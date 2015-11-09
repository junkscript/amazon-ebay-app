<?php if (!empty($filter->resultAll)): ?>
	<?php if (count($filter->getCondition($filter->resultAll)) > 0): ?>
		<h2><small>&nbsp;Condition</small></h2>
		<div id="condition">
			<div class="form-group">
				<?php foreach($filter->getCondition($filter->resultAll) as $key => $value): ?>
					<div class="radio">
						<label>
							<input type="radio" name="condition" value="<?= $key ?>" onchange="document.form_filter.submit()" <?php if (isset($_GET['condition']) && $_GET['condition'] == $key) echo 'checked'; ?> >
							<?php echo $value; ?>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<hr/>
	<?php endif; ?>
<?php endif; ?>