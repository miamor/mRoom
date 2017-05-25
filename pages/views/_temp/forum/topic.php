<div class="t-btns right">
	<a class="btn btn-default pull-right" href="?mode=new"><span class="fa fa-plus"></span> New topic</a>
</div>
<div class="clearfix"></div>

<div class="borderwrapm" style="margin-top:20px">
	<div class="maintitle floated dropped">
		<h3>Thông báo </h3>
	</div>
	<div class="maincontent sticky">
	<table class="ipbtable topic-list-box-forum">
		<thead>
			<tr>
				<th class="hidden time"></th>
				<th class="th-none icon"></th>
				<th class="forum2">Announcement &amp; Sticky</th>
				<th class="last last-post2">Last Posts</th>
			</tr>
		</thead>
		<tbody>
<? foreach ($topicsList[1] as $tO) { ?>
			<tr>
				<td class="hidden"><?php echo $tO['created'] ?></td>
				<td class="row2 centered">
					<div style="background-image:url(<?php echo IMG ?>/empty.jpg);" class="topicthumbnail"></div>
				</td>
				<td class="row2 topic-title" data-type="Announcement:">
					<img class="icon-status" title="No new posts" src="<?php echo IMG ?>/topic-icon.png" alt="No new posts"> <a class="topictitle" href="<? echo $tO['link'] ?>" title="<? echo $tO['title'] ?>"><? echo $tO['title'] ?></a> 
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
	</div>
</div>

<div class="borderwrapm tbmargin">
	<div class="maintitle">
		<h2>Topics</h2>
	</div>
	<table class="maincontent ipbtable topic-list-box-forum topics">
		<thead>
			<tr>
				<th class="hidden time"></th>
				<th class="th-none icon"></th>
				<th class="forum2">Topics</th>
				<th class="last last-post2">Last Posts</th>
			</tr>
		</thead>
		<tbody>
<? foreach ($topicsList[0] as $tO) { ?>
			<tr>
				<td class="hidden"><?php echo $tO['created'] ?></td>
				<td class="row2 centered">
					<div style="background-image:url(<?php echo IMG ?>/empty.jpg);" class="topicthumbnail"></div>
				</td>
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
</div>

<div class="t-btns right">
	<a class="btn btn-default pull-right" href="?mode=new"><span class="fa fa-plus"></span> New topic</a>
</div>
<div class="clearfix"></div>
