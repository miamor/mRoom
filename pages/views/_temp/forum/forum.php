<?php include 'pages/views/_temp/forum/statistics.php' ?>

<div class="col-lg-9 no-padding index-box">
<? foreach ($forumsList[0] as $fOne) { ?>
<div class="borderwrapm">
	<div class="maintitle floated clearfix">
		<h2><a href="<? echo $fOne['link'] ?>"><? echo $fOne['title'] ?></a></h2>
		<div class="contract" id="bc<? echo $fOne['id'] ?>" onclick="toggleCategory('c<? echo $fOne['id'] ?>');"> </div>
	</div>
	<div class="maincontent" id="c<? echo $fOne['id'] ?>">
		<table cellpadding="0" cellspacing="0" class="ipbtable index-box">
			<thead>
				<tr>
					<th class="statusIcon"> </th>
					<th class="row2 icon forum">Forum</th>
					<th class="row1 last post-info">Last Posts</th>
				</tr>
			</thead>
			<tbody>
<? foreach ($forumsList[$fOne['id']] as $fO) {
	extract($fO) ?>
				<tr data-id="<? echo $id ?>" class="childForum zzBox_<? echo $id ?>">
					<td class="centered"><span class="status"><img title="No new posts" src="http://i56.servimg.com/u/f56/18/59/49/93/13848621.png" alt="No new posts" class="icon"></span></td>
					<td class="row2 icon">
						<div class="par forum-name">
							<h3 class="hierarchy"><a href="<? echo $link ?>" class="forumtitle"><? echo $title ?></a></h3>
							<p class="forum-desc"><? echo $content ?></p>
						</div>
						<span class="topic4r stat4r">Chủ đề: <strong>13</strong></span>
						<span class="post4r stat4r">Bài viết: <strong>91</strong></span><span class="sub4r stat4r"></span>
					</td>
					<td class="row1">
					<? if ($lastpost) { ?>
						<span class="lastpost-avatar"><img src="<? echo $lastpost['author']['avatar'] ?>" alt=""></span>
						<span class="stat4r">
							<a class="last-post-link" href="<? echo $lastpost['tIn']['link'] ?>" title="<? echo $lastpost['tIn']['title'] ?>"><? echo $lastpost['tIn']['title'] ?></a>
							<div class="stat-time"><? echo $lastpost['created'] ?></div>
							<a href="<? echo $lastpost['author']['link'] ?>"><? echo $lastpost['author']['name'] ?></a>
							<a href="<? echo $lastpost['link'] ?>" class="last-post-icon"><img src="<? echo IMG ?>/lastpost1.png" alt="View latest post" title="View latest post"></a>
						</span>
					<? } ?>
					</td>
				</tr>
<? } ?>
			</tbody>
		</table>
	</div>
</div>
<? } ?>
</div>

<div class="col-lg-3">
</div>

<div class="clearfix"></div>
