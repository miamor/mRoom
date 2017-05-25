<?php /*if (!$m) {
	echo '<div class="contest_des" style="font-size:16px;margin-bottom:60px">'.$content.'</div>'; ?>
<div class="box box-success">
	<div class="box-header with-border">
		<i class="fa fa-warning"></i>
		<h3 class="box-title">Your scoreboard</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="total_score">Total score: <strong style="font-size:25px"><?php echo $totalScore ?></strong>/<span style="font-size:15px"><?php echo $totalMaxScore ?></span></div>

<table id="pList" class="p-submissions-list table table-border-wrap" style="margin-top:10px">
	<thead>
		<th class="col-lg-5">Problem</th>
		<th class="col-lg-2">Status</th>
		<th class="col-lg-3">AC</th>
		<th class="col-lg-2">Score</th>
	</tr></thead>
	<tbody>
	<?php foreach ($problems as $pO) { ?>
	<tr class="tc-<? echo ($pO['stt'] == 'error') ? 'WA' : ($pO['stt'] == 'success') ? 'AC' : 'default' ?>">
		<td><a href="<?php echo $pO['link'] ?>"><?php echo '['.$pO['code'].'] '.$pO['title'] ?></a></td>
		<td><?php if ($pO['AC']) echo $pO['AC'].'/'.$pO['tests'] ?></td>
		<td><?php echo $pO['stt'] ?></td>
		<td class="center"><?php echo $pO['score'] ?></td>
	</tr>
	<?php } ?>
</tbody></table>
	</div><!-- /.box-body -->
</div>
<?php } else { ?>	
<style>.c-main{padding:0!important}</style>
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
			else include 'pages/views/_temp/problems/submit.php' ?>
			</div> <!-- #tcode -->
		</div><!-- /.tab-content -->
	</div>

</div>
<?php } ?>
<?php if (!$m) {
	echo '<div class="contest_des" style="font-size:16px;margin-bottom:60px">'.$content.'</div>'; ?>
<div class="box box-success">
	<div class="box-header with-border">
		<i class="fa fa-warning"></i>
		<h3 class="box-title">Your scoreboard</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="total_score">Total score: <strong style="font-size:25px"><?php echo $totalScore ?></strong>/<span style="font-size:15px"><?php echo $totalMaxScore ?></span></div>

<table id="pList" class="p-submissions-list table table-border-wrap" style="margin-top:10px">
	<thead>
		<th class="col-lg-5">Problem</th>
		<th class="col-lg-2">Status</th>
		<th class="col-lg-3">AC</th>
		<th class="col-lg-2">Score</th>
	</tr></thead>
	<tbody>
	<?php foreach ($problems as $pO) { ?>
	<tr class="tc-<? echo ($pO['stt'] == 'error') ? 'WA' : ($pO['stt'] == 'success') ? 'AC' : 'default' ?>">
		<td><a href="<?php echo $pO['link'] ?>"><?php echo '['.$pO['code'].'] '.$pO['title'] ?></a></td>
		<td><?php if ($pO['AC']) echo $pO['AC'].'/'.$pO['tests'] ?></td>
		<td><?php echo $pO['stt'] ?></td>
		<td class="center"><?php echo $pO['score'] ?></td>
	</tr>
	<?php } ?>
</tbody></table>
	</div><!-- /.box-body -->
</div>
<?php } else { ?>	
<style>.c-main{padding:0!important}</style>
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
			else include 'pages/views/_temp/problems/submit.php' ?>
			</div> <!-- #tcode -->
		</div><!-- /.tab-content -->
	</div>

</div>
<?php }*/ ?>
