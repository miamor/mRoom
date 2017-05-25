<div style="margin-bottom:20px">
	<h3 class="left no-margin">Problems list</h3>
<?php if ($config->me['is_mod'] == 1) { ?>
	<div class="right">
		<a href="<?php echo $config->pLink.'?mode=new' ?>" class="btn btn-default pull-right"><span class="fa fa-plus"></span> Create new problem</a>
	</div>
<?php } ?>
	<div class="clearfix"></div>
</div>

<table id="pList" class="table table-border-wrap" style="margin-top:20px">
	<thead>
		<th class="th-none">ID</th>
		<th>Code</th>
		<th class="th-none"></th>
		<th>Title</th>
		<th>Language(s)</th>
		<th class="th-none"></th>
<!--		<th>Category</th> -->
		<th>Author</th>
		<th>Submitted on</th>
	</thead>
<?php foreach ($problemsList as $k => $row) {
	$k++;
	extract($row); ?>
	<tr id="<?php echo $id ?>" data-stt="<?php echo $stt ?>">
<!--		<td class="id"><a href="<?php echo $link ?>"><?php echo $id ?></a></td> -->
		<td class="id"><span class="gensmall">#</span><?php echo $k ?></td>
		<td class="code"><a href="<?php echo $link ?>"><?php echo $code ?></a></td>
		<td align="center"><div class="circle" style="background: #<?php echo $color ?>;"></div></td>
		<td><a href="<?php echo $link ?>"><?php echo $title ?></a></td>
		<td><?php echo $langTxt ?></td>
		<td class="difficulty">
			<div id="rate-implementation-box" class="progress problem-list-progress" title="<?php echo $totalAC.' AC of all '.$totalTests ?>">
				<div class="progress-bar progress-bar-<?php echo $perCls ?>" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="50" style="width: <?php echo $per ?>%;">
					<span><?php echo $per ?>%</span>
				</div>
			</div>
		</td>
<!-- 		<td><a class="problems-tags" href="<?php echo $config->cLink.'/'.$cat['link'] ?>"><span class="fa fa-tag"></span> <?php echo $cat['title'] ?></a></td> -->
		<td><a href="<?php echo $author['link'] ?>"><?php echo $author['name'] ?></a></td>
		<td><?php echo $created ?></td>
	</tr>
<?php } ?>
</table>
