<?php if (!empty($filter->resultAll)): ?>
	<?php if (count($filter->getBrands($filter->resultAll)) > 0): ?>
		<h2><small>&nbsp;Brand</small></h2>
		<div id="brand">
			<div class="form-group">
				<?php foreach($filter->getBrands($filter->resultAll) as $key => $value): ?>
					<div class="radio">
						<label>
							<input type="radio" name="brand" value="<?=$key ?>" onchange="document.form_filter.submit()" <?php if (isset($_GET['brand']) && $_GET['brand'] == $key) echo 'checked'; ?> >
							<?php echo $value; ?>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<hr/>
	<?php endif; ?>
<?php endif; ?>