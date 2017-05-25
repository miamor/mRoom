<h2>Search "<?php echo $search->keyword ?>"</h2>

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#problems" data-toggle="tab" aria-expanded="false">Problems</a></li>
		<li><a href="#contests" data-toggle="tab">Contests</a></li>
		<li><a href="#topics" data-toggle="tab">Topics</a></li>
		<li><a href="#users" data-toggle="tab">Users</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="problems">
			<h3>Problems</h3>
<table id="pList" class="table table-border-wrap">
	<thead>
		<th class="th-none">ID</th>
		<th>Code</th>
		<th class="th-none"></th>
		<th>Title</th>
		<th>Language(s)</th>
		<th>Submitter</th>
	</thead>
<?php foreach ($search->response['problems'] as $k => $row) {
	$k++;
	extract($row); ?>
	<tr id="<?php echo $id ?>" data-stt="<?php echo $stt ?>">
<!--		<td class="id"><a href="<?php echo $link ?>"><?php echo $id ?></a></td> -->
		<td class="id"><span class="gensmall">#</span><?php echo $k ?></td>
		<td class="code"><a href="<?php echo $link ?>"><?php echo $code ?></a></td>
		<td align="center"><div class="circle" style="background: #<?php echo $color ?>;"></div></td>
		<td><a href="<?php echo $link ?>"><?php echo $title ?></a></td>
		<td><?php echo $langTxt ?></td>
		<td><a href="<?php echo $author['link'] ?>"><?php echo $author['name'] ?></a></td>
	</tr>
<?php } ?>
</table>
		</div> <!-- .tab-pane.problems -->
		
		<div class="tab-pane" id="contests">
	<h3>Contests</h3>
<table id="cList" class="table table-border-wrap">
	<thead>
		<th class="th-none">ID</th>
		<th>Code</th>
		<th>Status</th>
		<th>Title</th>
		<th>Problems</th>
		<th>Submitter</th>
		<th>Start</th>
		<th>Time</th>
	</thead>
<?php foreach ($search->response['contests'] as $k => $row) {
	$k++; ?>
	<tr class="<?php if ($row['stt'] == 1) echo 'tc-AC'; //else if ($row['stt'] == -1) echo 'tc-WA' ?>" id="<?php echo $row['id'] ?>">
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
		</div><!-- .contests -->
		
		<div class="tab-pane" id="topics">
	<h3>Topics</h3>
	<table id="tList" class="topics table table-border-wrap" style="width:100%">
		<thead>
			<tr>
				<th class="hidden time"></th>
				<th class="forum2">Topics</th>
				<th>Last post</th>
			</tr>
		</thead>
		<tbody>
<? foreach ($search->response['topics'] as $tO) { ?>
			<tr>
				<td class="hidden"><?php echo $tO['created'] ?></td>
				<td class="row2 topic-title">
					<img class="icon-status" title="No new posts" src="<?php echo IMG ?>/topic-icon.png" alt="No new posts">
					<a class="topictitle" href="<? echo $tO['link'] ?>" title="<? echo $tO['title'] ?>"><? echo $tO['title'] ?></a> 
					<div class="topic-stat">
						<img src="<?php echo IMG ?>/file-icon.png" alt="topic" title="topic"> 
						<span class="topic4r stat4r">Tác giả: <a href="<? echo $tO['author']['link'] ?>"><strong><? echo $tO['author']['name'] ?></strong></a></span>
						<span class="topic4r stat4r">Gửi lúc: <? echo $tO['created'] ?></span>
						<span class="post4r stat4r">Trả lời: <strong><? echo $tO['replies'] ?></strong></span>
						<span class="post4r stat4r">Lượt xem: <strong><? echo $tO['views'] ?></strong></span>
					</div>
				</td>
				<td class="row1 lastaction">
					<span class="lastpost-avatar">
						<img src="<? echo $tO['lastpost']['author']['avatar'] ?>" alt="">
					</span>
					<div class="stat4r">
						on <? echo $tO['lastpost']['created'] ?><br><a href="<? echo $tO['lastpost']['author']['link'] ?>" class="gensmall"><strong><? echo $tO['lastpost']['author']['name'] ?></strong></a>
						<a href="<? echo $tO['link'].'#'.$tO['posts'] ?>"><img src="<?php echo IMG ?>/lastpost1.png" alt="View latest post" title="View latest post"></a>
					</div>
				</td>
			</tr>
<? } ?>
		</tbody>
	</table>
		</div> <!-- .topics -->
		
		<div class="tab-pane" id="users">
			<h3>Users</h3>
	<table id="uList" class="topics table table-border-wrap" style="width:100%">
		<thead>
			<tr>
				<th class="hidden time"></th>
				<th class="forum2">Topics</th>
				<th>Last post</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
		</div> <!-- users -->
	</div>
</div>
