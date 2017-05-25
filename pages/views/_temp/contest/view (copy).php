<div class="c-view">
	<div class="col-lg-9 c-main">
		<?php include 'cMain.php' ?>
	</div>
	
	<div class="col-lg-3 c-sidebar">
		<div class="c-sidebar-head">
			<h2><?php echo $title ?></h2>
			<div class="c-time">
				<?php echo $test_time[0].':'.$test_time[1] ?>
			</div>
			<div class="c-time-left"><?php if ($timeOut == true) echo '00:00:00' ?></div>
			<div class="clearfix"></div>
			<div class="c-sidebar-details">
				<div class="start_time">Start time: <?php echo $test_start ?></div>
				<div class="end_time">End time: <?php echo $test_end ?></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<ul class="c-problems-list">
			<li <?php if (!$m) echo 'class="active"' ?> id="home"><a href="<?php echo $link ?>">Home</a></li>
		<?php foreach ($problems as $pO) { ?>
			<li <?php if (isset($m) && $m == $pO['code']) echo 'class="active"' ?> id="<?php echo $pO['id'] ?>"><a href="<?php echo $pO['link'] ?>">[<?php echo $pO['code'] ?>] <?php echo $pO['title'] ?></a></li>
		<?php } ?>
			<li <?php if ($m == 'discussions') echo 'class="active"' ?> id="discussions"><a href="<?php echo $link.'/discussions' ?>">Discussions</a></li>
		</ul>
	</div>
	
	<div class="clearfix"></div>
</div>
