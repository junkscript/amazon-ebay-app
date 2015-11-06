<?php if ($filter->totalResult > 0): ?>
	<h1><small>Total results: <?= $filter->totalResult; ?></small></h1>
<?php endif; ?>
<div id="remove-filters">
  <div class="form-group">
    <?php if (isset($_GET['store'])): ?>
      <?php if ($_GET['store'] != 'all'): ?>
        <a class='remove-link btn btn-info' href='<?= $filter->removeFromUrl('store', '') ?>'>
        <?= 'Store: ' . strtoupper($_GET['store'][0]) . substr($_GET['store'], 1) ?>&nbsp;<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['condition'])): ?>
      <?php if ($_GET['condition'] != 'all'): ?>
        <a class='remove-link btn btn-info' href='<?= $filter->removeFromUrl('condition', '') ?>'>
        <?= 'Condition: ' . strtoupper($_GET['condition'][0]) . substr($_GET['condition'], 1) ?>&nbsp;<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
      <?php endif; ?>
    <?php endif; ?>

    <?php if(isset($_GET['brand'])): ?>
      <?php if ($_GET['brand'] != 'all'): ?>
        <a class='remove-link btn btn-info' href='<?= $filter->removeFromUrl('brand', '') ?>'>
        <?= 'Brand: ' . strtoupper($_GET['brand'][0]) . substr($_GET['brand'], 1) ?>&nbsp;<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
      <?php endif; ?>
    <?php endif; ?>

    <?php if(isset($_GET['shipping'])): ?>
      <?php if ($_GET['shipping'] != 'all'): ?>
        <a class='remove-link btn btn-info' href='<?= $filter->removeFromUrl('shipping', '') ?>'>
        <?= 'Shipping: ' . strtoupper($_GET['shipping'][0]) . substr($_GET['shipping'], 1) ?>&nbsp;<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($result)) { ?>
  <?php foreach($result as $key => $value) : ?>
  	<div class="col-md-2 col-sm-4 col-xs-6">
      <div class="product">
      	<img src="<?=$value['galleryURL']; ?>" height=96 class="text-center">
    		<div class="title" class="small">
        	<a href="goto.php/'<?= base64_encode($value['viewItemURL']);?>'" target="_blank">
          	<?= $value['title']; ?>
        	</a>
      	</div>
      	<div class="price">
        	<?= setSymbolFromCyrrencyId($value['priceId']) . number_format($value['currentPrice'], 2, '.', ','); ?>
        	<?php if (isset($value['oldPrice'])): ?>
        		<del><?= setSymbolFromCyrrencyId($value['priceId']) . number_format($value['oldPrice'], 2, '.', ','); ?></del>
        	<?php endif;?>
      	</div>
      	<h5><i><small><?= $value['shopName'];?></small></i></h5>
    	</div>
  	</div>
  <?php endforeach;?> 
<?php } else { ?>
  <h3><small>No result!</small></h3>
<?php } ?>