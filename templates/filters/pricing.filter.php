<?php if (!empty($filter->resultAll)): ?>
  <?php if (count($filter->getPricing($filter->resultAll)) > 0): ?>
    <h2><small>&nbsp;Pricing</small></h2>
    <div id="pricing">
      <div class="form-group">
        <?php foreach($filter->getPricing($filter->resultAll) as $key => $value): ?>
          <div class="radio">
            <label>
              <input type="radio" name="pricing" value="<?= $key ?>" onchange="document.form_filter.submit()" <?php if (isset($_GET['pricing']) && $_GET['pricing'] == $key) echo 'checked'; ?> >
              <?php echo $value; ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <hr/>
  <?php endif; ?>
<?php endif; ?>