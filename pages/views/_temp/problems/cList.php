<div class="box box-solid">
	<div class="box-header with-border">
		<i class="fa fa-tags"></i>
		<h3 class="box-title">Categories</h3>
	</div>
	<div class="box-body">
<?php foreach ($catList as $cO) { ?>
		<a class="problems-tag" href="<?php echo $cO['title'] ?>"><?php echo $cO['title'] ?> <span class="tag-num"><?php echo $cO['num'] ?></span></a>
<?php } ?>
	</div>
</div>
