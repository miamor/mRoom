<div class="col-lg-3 u-sidebar no-padding">
	<div class="u-title">
		<h2><?php echo $name ?></h2>
	</div>
	<div class="u-sidebar-head centered">
		<a href="<?php echo $link ?>" data-online="<?php echo $online ?>">
			<img class="u-avatar" src="<?php echo $avatar ?>"/>
		</a>
		<div class="u-rank">
			<span>#</span><strong><?php echo $rank ?></strong>
		</div>
		<div class="u-sta sta-list">
			<div class="sta-one u-submissions">
				<strong><?php echo $submissions ?></strong>
				submissions
			</div>
			<div class="sta-one u-score">
				<strong><?php echo $score ?></strong>
				avarage
			</div>
			<div class="sta-one u-submissions">
				<strong><?php echo $AC ?><span>/<?php echo $total_tests ?></span></strong>
				AC
			</div>
		</div>
	</div>
	<div class="txt-with-line">
		<span class="txt generate-new-button">More</span>
	</div>
	<ul class="u-more">
		<li <?php if (!$m) echo 'class="active"' ?> id="home"><a href="<?php echo $link ?>"><i class="fa fa-home"></i> Home</a></li>
		<li <?php if ($m == 'submissions') echo 'class="active"' ?> id="submissions"><a href="<?php echo $link.'/submissions' ?>"><i class="fa fa-list"></i> Submissions</a></li>
		<li <?php if ($m == 'teams') echo 'class="active"' ?> id="teams"><a href="<?php echo $link.'/teams' ?>"><i class="fa fa-users"></i> Teams</a></li>
		<li <?php if ($m == 'blogs') echo 'class="active"' ?> id="blogs"><a href="<?php echo $link.'/blogs' ?>"><i class="fa fa-comments"></i> Blogs</a></li>
		<?php if ($config->u === $user->id) { ?>
			<li <?php if ($m == 'settings') echo 'class="active"' ?> id="settings"><a href="<?php echo $link.'/settings' ?>"><i class="fa fa-cogs"></i> Settings</a></li>
		<?php } ?>
	</ul>
</div>
