<div class="box box-success">
	<div class="box-header with-border">
		<i class="fa fa-trophy"></i>
		<h3 class="box-title">Top teams</h3>
	</div>
	<div class="box-body">
<ol class="olList group_poster">
<? foreach ($topTeams as $tO) { ?>
<li>
	<div class="lasttopic-title col-lg-9 no-padding-right">
		<a href="<? echo $tO['link'] ?>"><? echo $tO['title'] ?></a>
	</div>
	<div class="col-lg-3 text-right no-padding-right">
		<? echo $tO['score'] ?>
	</div>
</li>
<? } ?>
</ol>
	</div>
</div>

