<div class="p-view">
	<div class="p-tit left col-lg-8 no-padding">
		<h2 class="p-title"><?php echo $problem->title ?>
		<strong class="p-code"><a href='./<?php echo $problem->code ?>'><?php echo $problem->code ?></a></strong></h2>
	</div>

	<div class="nav-tabs-customs">
		<ul class="nav nav-tabs right col-lg-4 no-padding-right">
			<li class="<?php if (!$isSubPage) echo 'active' ?>"><a <?php if (!$isSubPage) echo 'href="#tinfo" data-toggle="tab"'; else echo 'href="./"' ?>>Info</a></li>
			<li><a <?php if (!$isSubPage) echo 'href="#tcode" data-toggle="tab"'; else echo 'href="./"' ?>>Code</a></li>
			<li class="<?php if ($isSubPage) echo 'active' ?>"><a href="<?php echo $problem->link ?>/submissions">Submissions</a></li>
		<?php if ($problem->uid === $config->u || $config->me['is_mod'] === 1) { ?>
			<li class="dropdown right">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
					<i class="fa fa-cog"></i> <span class="caret"></span>
				</a>
				<ul class="dropdown-menu pull-right problem-settings">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="p_hide" class="setting" title="Hide this problem"><?php echo ($problem->stt == -1) ? 'Show' : 'Hide' ?></a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $problem->link.'?mode=edit' ?>" id="p_edit" title="Edit this problem">Edit</a></li>
					<li role="presentation" class="divider"></li>
					<li role="presentation disabled"><a role="menuitem" tabindex="-1" href="#" class="text-error" id="p_del" class="setting" title="Permanently delete this problem">Delete</a></li>
				</ul>
			</li>
		<?php } ?>
		</ul>
		<div class="clearfix"></div>
		<div class="p-view-content tab-content">
			<?php if (!$isSubPage) { ?>
			<div class="tab-pane active" id="tinfo">
				<?php include 'info.php' ?>
			</div><!-- #tinfo -->

			<div class="tab-pane" id="tcode">
				<?php include 'submit.php' ?>
			</div> <!-- #tcode -->
			<?php } else { ?>
			<div class="tab-pane active" id="tsubmissions">
				<?php if ($isSubPage) include 'submissions.php' ?>
			</div> <!-- #tsubmissions -->
			<?php } ?>
		</div><!-- /.tab-content -->
	</div>

</div>
