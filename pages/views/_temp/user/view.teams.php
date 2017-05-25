<div class="text-right right">
	<a class="btn btn-default pull-right" href="?mode=new"><span class="fa fa-plus"></span> New team</a>
</div>
<div class="clearfix"></div>

<table class="teams-list table table-border-wrap table-striped" style="margin-top:10px">
	<thead>
		<th>#</th>
		<th class="col-lg-4">Your team</th>
		<th class="col-lg-6">Members</th>
		<th class="center">Score</th>
		<th class="center">Submissions</th>
	</tr></thead>
	<tbody>
	<?php foreach ($mList as $tK => $tO) { ?>
	<tr>
		<td><?php echo $tO['rank'] ?></td>
		<td class="no-padding" style="vertical-align:middle!important"><a href="<?php echo $tO['link'] ?>"><?php echo $tO['title'] ?></a></td>
		<td style="vertical-align:middle!important"><?php foreach ($tO['members'] as $mK => $mO) {
			echo '<a href="'.$mO['link'].'">'.$mO['name'].'</a>';
			if ($mK < $tO['usersNum']-1) echo ', ';
		} ?></td>
		<td class="center" style="vertical-align:middle!important"><?php echo $tO['score'] ?></td>
		<td class="center" style="vertical-align:middle!important"><?php echo $tO['submissions'] ?></td>
	</tr>
	<?php } ?>
</tbody></table>
