<div class="last_topic">
	<h3>THỐNG KÊ BÀI VIẾT</h3>
	<div class="maincontent" id="cstat">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs h">
				<li class="active"><a href="#recent" data-toggle="tab" aria-expanded="false">Bài viết mới</a></li>
				<li class=""><a href="#active" data-toggle="tab" aria-expanded="true">Chủ đề sôi nổi</a></li>
				<li><a href="#view" data-toggle="tab">Chủ đề xem nhiều</a></li>
				<li class="pull-right dropdown">
					<i class="fa fa-gear"></i>
					<ul class="dropdown-menu">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
					</ul>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active box-content topicsLast_div" id="recent">
				<? $topicsAr = $lastTopic; include 'staPost.php'; ?>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane box-content topicsLast_div" id="active">
				<? $topicsAr = $activeTopic; include 'staPost.php'; ?>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane box-content topicsLast_div" id="view">
				<? $topicsAr = $viewsTopic; include 'staPost.php'; ?>
				</div>
				<!-- /.tab-pane -->
			</div>
			<!-- /.tab-content -->
		</div>
	</div>
</div>
