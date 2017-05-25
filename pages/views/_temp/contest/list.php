<section class="problems-list">

<div style="margin-bottom:20px">
	<h3 class="left no-margin">Contests</h3>
<?php if ($config->me['is_mod'] == 1) { ?>
	<div class="right">
		<a href="<?php echo $config->cLink.'?mode=new' ?>" class="btn btn-default pull-right"><span class="fa fa-plus"></span> New contest</a>
	</div>
<?php } ?>
	<div class="clearfix"></div>
</div>

<table id="pList" class="table table-border-wrap" style="margin-top:20px">
	<thead>
		<th class="th-none">ID</th>
		<th>Code</th>
		<th>Status</th>
		<th>Title</th>
		<th>Problems</th>
		<th>Author</th>
		<th>Start</th>
		<th>Time</th>
	</thead>
<?php foreach ($cList as $k => $row) {
	$k++; ?>
	<tr class="<?php if ($row['stt'] == 1) echo 'tc-AC'; //else if ($row['stt'] == -1) echo 'tc-WA' ?>" id="<?php echo $row['id'] ?>">
<!--		<td class="id"><a href="<?php echo $link ?>"><?php echo $row['id'] ?></a></td> -->
		<td class="id"><span class="gensmall">#</span><?php echo $k ?></td>
		<td class="code" style="width:80px"><a href="<?php echo $row['link'] ?>"><?php echo $row['code'] ?></a></td>
		<td class="status" style="width:120px">
			<?php echo $row['status'] ?>
		</td>
		<td><a href="<?php echo $row['link'] ?>"><?php echo $row['title'] ?></a></td>
		<td class="centered" style="width:80px"><?php echo count($row['problems']) ?></td>
		<td><a href="<?php echo $row['author']['link'] ?>"><?php echo $row['author']['name'] ?></a></td>
		<td style="width:160px"><?php echo $row['test_start'] ?></td>
		<td><?php echo $row['test_time'] ?></td>
	</tr>
<?php } ?>
</table>

</section>
