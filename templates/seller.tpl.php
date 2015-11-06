<?php if (!empty($filter->topSellers)): ?>
	<h2><small>Top seller at Amazon</small></h2>
	<div id ="top-sellers">
		<hr/>
		<?php foreach ($filter->topSellers as $key => $value): ?>
		  	<div class="clearfix" >
		      <a href="goto.php/'<?= base64_encode($value['viewItemURL']);?>'" target="_blank">
		        <img src="<?=$value['galleryURL']; ?>" width=50 height=50>
		        <div style="margin-left: 60px;">
			        <small><?= strlen($value['title']) > 50 ? substr($value['title'], 0, 50) . '...' : $value['title']; ?></small>
			        <br/>
			        <?php if (isset($value['currentPrice'])): ?>
			        	<span style="color: #000000;"><?= setSymbolFromCyrrencyId($value['priceId']) . number_format($value['currentPrice'], 2, '.', ','); ?></span>
		        	<?php endif; ?>
		        </div>
		      </a>
		    </div>
    	  <hr/>
		<?php endforeach; ?>
	</div>
<?php endif; ?>