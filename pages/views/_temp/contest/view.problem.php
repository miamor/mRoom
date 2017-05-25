<div class="p-view">
	<div class="p-tit left col-lg-8 no-padding">
		<h2 class="p-title"><?php echo $problem->title ?>
		<strong class="p-code"><a href='./<?php echo $problem->code ?>'><?php echo $problem->code ?></a></strong></h2>
	</div>

	<div class="nav-tabs-customs">
		<ul class="nav nav-tabs right col-lg-4 no-padding-right">
			<li class="active"><a href="#tinfo" data-toggle="tab">Info</a></li>
			<li><a href="#tcode" data-toggle="tab">Code</a></li>
		</ul>
		<div class="clearfix"></div>
		<div class="p-view-content tab-content">
			<div class="tab-pane active" id="tinfo">
				<?php include 'pages/views/_temp/problems/info.php' ?>
			</div><!-- #tinfo -->

			<div class="tab-pane" id="tcode">
			<?php if ($timeOut == true) include 'pages/views/_temp/problems/submit_timeout.php';
			else if (!$contest->uid) include 'pages/views/_temp/problems/submit_not_join.php';
			else include 'pages/views/_temp/problems/submit.php' ?>
			</div> <!-- #tcode -->
		</div><!-- /.tab-content -->
	</div>

</div>
