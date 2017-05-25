<h2><?php echo $dir ?></h2>
<?php foreach ($filesAr as $fO) {
	extract($fO) ?>
<div class="one-file" id="menu-link">
	<div class="file-name">
		<a class="left" href="<?php if (is_dir($fullDir.'/'.$fN)) echo '?dir='.$dir.'/'.$fN; else echo $dir.'/'.$fN ?>" data-type="<?php echo $fType ?>">
		<?php if (is_dir($fullDir.'/'.$fN)) echo '<span class="fa fa-folder"></span> ';
			else echo '<span class="fa fa-file'.$fTypeTxt.'-o"></span> ';
			echo $fN;
		?>
		</a>
		<?php if (is_file($fullDir.'/'.$fN)) { ?>
			<span class="right file-size gensmall"><span class="num"><?php echo filesize($fullDir.'/'.$fN) ?></span> bytes</span>
		<?php } ?>
		<div class="clearfix"></div>
	</div>
	<?php if (is_file($fullDir.'/'.$fN)) { ?>
	<div class="file-action">
		<div class="file-download left"><a target="_blank" href="<?php echo $fullDir.'/'.$fN ?>"><span class="fa fa-download"></span> Preview</a></div>
		<div class="file-preview left"><a target="_blank" href="<?php echo $fullDir.'/'.$fN ?>"><span class="fa fa-eye"></span> Download</a></div>
		<div class="clearfix"></div>
	</div>
	<?php } ?>
</div>
<?php } 
