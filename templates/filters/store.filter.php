<?php if (!empty($filter->resultAll)): ?>
	<h2><small>&nbsp;Store</small></h2>
	<div id="store">
		<div class="form-group">
			<?php foreach($filter->store as $key => $value): ?>
				<div class="radio">
					<label>
						<input type="radio" name="store" value="<?= $key ?>" onchange="document.form_filter.submit()" <?php if (isset($_GET['store']) && $_GET['store'] == $key) echo 'checked'; ?> >
						<?php echo $value; ?>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<hr/>
<?php endif; ?>