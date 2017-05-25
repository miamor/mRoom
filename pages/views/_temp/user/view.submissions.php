<h3>Submissions</h3>

<div class="submission-list">
<table id="pList" class="p-submissions-list table table-border-wrap" style="margin-top:20px">
    <thead>
        <th class="th-none">ID</th>
        <th class="col-lg-7"><span class="fa fa-user"></span> Author</th>
        <th>Score</th>
		<?php //for ($i = 0; $i < $problem->tests; $i++) echo '<th class="th-none"></th>' ?>        
        <th class="col-lg-3 center"><span class="fa fa-clock-o"></span> Submit time</th>
    </thead>
<?php foreach ($mList as $k => $row) {
	$k++; ?>
    <tr id="<?php echo $row['id'] ?>" class="ranker rank-<?php echo $k ?> tc-<?php if ($row['compile_stt'] != 0) echo 'WA'; else if ($row['score'] == 100) echo 'AC bold'; ?>" data-id="<?php echo explode('.', end(explode('/', $row['file'])))[0] ?>" data-pid="<?php echo $row['iid'] ?>" data-mode="<?php echo $row['lang'] ?>" data-uid="<?php echo ($row['tid']) ? 't'.$row['tid'] : $row['uid'] ?>">
        <td class="id"><a href="<?php echo $row['link'] ?>"><?php echo $k ?></a></td>
        <td>
			<?php if ($row['tid']) echo '<span class="fa fa-star text-warning" title="Submit for test"></span>'; ?>
			<a href="<?php echo $row['pInfo']['link'] ?>">[<?php echo $row['pInfo']['code'] ?>] <?php echo $row['pInfo']['title'] ?></a>
		</td>
        <td><?php echo $row['score'] ?></td>
		<td><?php echo $row['created'] ?></td>
    </tr>
<?php } ?>
</table>


<div class="p-submission-details no-padding-right" style="padding-bottom:40px">
	<div class="col-lg-6 p-sub no-padding">
	</div>

	<div class="col-lg-6 no-padding-right">
		<div class="p-sub-score col-lg-8 no-padding"></div>
		<div class="clearfix"></div>
		<div class="tab-pane" id="console">
			<div class="me-sub-compile" id="me-sub-compile"></div>
		</div><!-- #console -->
		<div class="tab-pane" id="noti">
			<div class="me-sub-compile" id="me-sub-noti"></div>
		</div><!-- #noti -->
		<div class="tab-pane" id="plachecker">
			<div class="me-sub-compile" id="me-sub-plachecker"></div>
		</div><!-- #plachecker -->
	</div>
	<div class="clearfix"></div>
</div>
</div>
