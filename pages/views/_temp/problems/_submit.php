<div class="files-saved col-lg-2 no-padding">
	<h4 class="left">Your files</h4>
	<ul class="files-saved-more right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" title="Add new submission"><span class="fa fa-plus"></span></a>
			<ul class="dropdown-menu new-file">
				<li role="presentation"><a role="menuitem" tabindex="-1" id="c_cpp" href="#">C++</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" id="c" href="#">C</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" id="java" href="#">Java</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" id="python" href="#">Python 2.7</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" id="python" href="#">Python 3.2</a></li>
			</ul>
		</li>
	</ul>
	<ul class="list-group me-sub-test-list me-files clearfix">
	<?php foreach ($myCodeFile as $mk => $myFile) {
		if (file_exists($myFile)) {
			$fs = explode('.', $myFile);
			$ext = end($fs); ?>
		<li class="list-group-item media me-file <?php if ($mk == 0) echo 'active' ?>" data-id="<?php echo end(explode('/', $fs[1])) ?>" data-lang="<?php echo $ext ?>">
			<img style="margin:2px 4px -1px 0" class="media-object left" src="<?php echo ASSETS ?>/dist/img/<?php echo $ext ?>.png" width="12" height="12"> <?php echo end(explode('/', $myFile)) ?>
			<div class="clearfix"></div>
		</li>
	<?php 	}
	} ?>
	</ul>
</div>
<form class="code-area col-lg-10 no-padding-right">
	<div id="me-sub-code-area" class="aceditor"><?php echo $myCode ?></div>
	<input id="sub-num" name="fnum" class="hide" value="1"/>
	<textarea id="sub-code" name="code" class="non-sce hide"></textarea>
	<textarea id="sub-code-formatted" name="code-formatted" class="non-sce hide"></textarea>
	<div class="code-tool nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li><a href="#noti" data-toggle="tab">Notification</a></li>
			<li class="active"><a href="#console" data-toggle="tab">Console</a></li>
			<li class="pull-right">
				<div id="code-submit" class="btn btn-success right">Submit</div>
				<div id="code-compile" class="btn btn-danger right">Compile</div>
				<select name="mode" id="mode" class="form-control right" style="width:250px" size="1">
					<option selected value="cpp">C/C++</option><option disabled value="csharp">C#</option><option disabled value="java">Java</option><option disabled value="pascal">Pascal</option>
				</select>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane" id="noti">
				<div class="me-sub-compile" id="me-sub-noti"></div>
			</div><!-- /.tab-pane -->
			<div class="tab-pane active" id="console">
				<div class="me-sub-compile" id="me-sub-compile"></div>
			</div><!-- /.tab-pane -->
		</div><!-- /.tab-content -->
	</div><!-- nav-tabs-custom -->
</form>

<script>var codeAreaID = 'me-sub-code-area';</script>
<?php $exJS['dist'][] = 'beautify.js' ?>
