<div class="contest_des" style="font-size:16px;margin-bottom:60px"><?php echo $content ?></div>

<?php 
if (!$contest->uid && $timeOut == false) { ?>
<div class="col-lg-2 no-padding text-center">
	<a class="btn btn-success btn-lg cjoin" href="#">Join now!</a>
	<div class="gensmall center" style="margin-top:5px">as a person</div>
</div>
<div class="col-lg-1 no-padding text-center gensmall" style="padding-top:15px!important">or</div>
<div class="col-lg-9 no-padding">
<h3 class="box-title no-margin">Join as a team</h3>
<table class="teams-list table table-border-wrap table-striped" style="margin-top:10px">
	<thead>
		<th class="col-lg-4">Your team</th>
		<th class="col-lg-6">Members</th>
		<th class="col-lg-2"></th>
	</tr></thead>
	<tbody>
	<?php foreach ($teamsList as $tK => $tO) { ?>
	<tr>
		<td style="vertical-align:middle!important"><a href="<?php echo $tO['link'] ?>"><?php echo $tO['title'] ?></a></td>
		<td style="vertical-align:middle!important"><?php foreach ($tO['members'] as $mK => $mO) {
			echo '<a href="'.$mO['link'].'">'.$mO['name'].'</a>';
			if ($mK < $tO['usersNum']-1) echo ', ';
		} ?></td>
		<td style="vertical-align:middle!important"><a class="btn btn-success cjoin" href="#" id="<?php echo $tO['id'] ?>">Join</a></td>
	</tr>
	<?php } ?>
</tbody></table>
</div>
<div class="clearfix"></div>
<?php }
if ($hasSubmit == true) { ?>
<div class="box box-success">
	<div class="box-header with-border">
		<i class="fa fa-warning"></i>
		<h3 class="box-title">Your <?php echo ($contest->team) ? 'team' : '' ?> scoreboard</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
	<?php if ($contest->team) echo 'Team: <a href="'.$team->link.'">'.$team->title.'</a>';
		else echo '<a href="'.$config->me['link'].'">'.$config->me['name'].'</a>' ?>
		<div class="total_score" style="margin-top:7px">Total score: <strong style="font-size:25px"><?php echo $totalScore ?></strong>/<span style="font-size:15px"><?php echo $totalMaxScore ?></span></div>

<table id="pList" class="p-submissions-list table table-border-wrap table-striped" style="margin-top:10px">
	<thead>
		<th class="col-lg-4">Problem</th>
		<th class="col-lg-2 center">Status</th>
		<th class="col-lg-2 center">AC</th>
		<th class="col-lg-2 center">Score</th>
		<th class="col-lg-2 center">Submitter</th>
	</tr></thead>
	<tbody>
	<?php foreach ($problems as $pO) { ?>
	<tr class="tc-<? echo ($pO['stt'] == 'error') ? 'WA' : ($pO['stt'] == 'success') ? 'AC' : 'default' ?>">
		<td class="col-lg-4"><a href="<?php echo $pO['link'] ?>"><?php echo '['.$pO['code'].'] '.$pO['title'] ?></a></td>
		<td class="col-lg-2 center"><?php if ($pO['AC']) echo $pO['AC'].'/'.$pO['tests'] ?></td>
		<td class="col-lg-2 center"><?php echo $pO['stt'] ?></td>
		<td class="col-lg-2 center"><?php echo $pO['score'] ?></td>
		<td class="col-lg-2 center"><a href="<?php echo $pO['submitter']['link'] ?>"><?php echo $pO['submitter']['name'] ?></a></td>
	</tr>
	<?php } ?>
</tbody></table>
	</div><!-- /.box-body -->
</div>
<?php }
if (!$contest->uid) echo '<div class="alerts alert-info">You and your team did not join this test.</div>' ?>
