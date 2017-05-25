<?php foreach ($filesAr as $fO) { ?>
<li class="one-menu left-menu-one" id="menu-link">
	<a data-dir="<?php echo $fO['fN'] ?>" href="<?php echo $config->fLink.'?dir='.$fO['fN'] ?>"><?php echo $fO['fN'] ?></a>
</li>
<?php } 
