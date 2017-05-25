<h2 style="margin:0 0 20px">Blogs</h2>
<table id="tList" class="ipbtable dataTable table table-striped">
	<thead>
		<tr>
			<th class="th-none hidden"></th>
			<th class="th-none hidden"></th>
			<th class="th-none hidden"></th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($mList as $oneTopic) { ?>
	<tr class="one-topic">
		<td valign="top" class="hidden"><?php echo $oneTopic['created'] ?></td>
		<td valign="top" class="col-lg-1 no-padding-right postprofile">
			<a title="<?php echo $oneTopic['author']['name'] ?>" href="<?php echo $oneTopic['author']['link'] ?>" class="postprofile-avatar" data-online="<?php echo $oneTopic['author']['online'] ?>"><img class="avatar" style="margin:0" src="<?php echo $oneTopic['author']['avatar'] ?>"></a>
		</td>
		<td valign="top" class="col-lg-11">
			<h4 class="dc-title"><a title="<?php echo $oneTopic['title'] ?>" href="<?php echo $oneTopic['link'] ?>"><?php echo $oneTopic['title'] ?></a></h4>
			<div class="dc-content"><?php echo $oneTopic['content'] ?></div>
			<div class="dc-more">
				<div class="dc-cat left gensmall">
					<span class="fa fa-tag"></span> 
					<a href="<?php echo $oneTopic['cat']['link'] ?>"><?php echo $oneTopic['cat']['title'] ?></a>
				</div>
				<div class="dc-time right gensmall"><span class="fa fa-clock-o"></span> <?php echo $oneTopic['created'] ?></div>
			</div>
		</td>
	</tr>
<?php } ?>
	</tbody>
</table>
