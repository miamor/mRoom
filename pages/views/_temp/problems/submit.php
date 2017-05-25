<form class="code-area">
	<div class="code-sub-area">
		<div class="code-editor-area">
			<input id="sub-num" name="fnum" class="hide" value="<?php echo count($listFiles) ?>"/>
			<input id="mode" name="mode" class="hide" value=""/>
			<textarea id="sub-code" name="code" class="non-sce hide"></textarea>
			<textarea id="sub-code-formatted" name="code-formatted" class="non-sce hide"></textarea>

<div class="files-saved col-lg-2 no-padding">
	<h4>Your files</h4>
	<ul class="files-saved-more right">
		<li class="dropdown pull-right">
			<a class="dropdown-toggle" id="new-file" data-toggle="dropdown" href="#" aria-expanded="false" title="Add new submission"><span class="fa fa-plus"></span></a>
			<ul class="dropdown-menu new-file">
			<?php foreach ($problem->lang as $oneL) {
				if ($oneL == 'cpp') echo '<li role="presentation"><a role="menuitem" tabindex="-1" id="c_cpp" href="#">C++</a></li>';
				if ($oneL == 'c') echo '<li role="presentation"><a role="menuitem" tabindex="-1" id="c" href="#">C</a></li>';
				if ($oneL == 'java') echo '<li role="presentation"><a role="menuitem" tabindex="-1" id="java" href="#">Java</a></li>';
				if ($oneL == 'python') echo '<li role="presentation"><a role="menuitem" tabindex="-1" id="python" href="#">Python 2.7</a></li>';
			} ?>
			</ul>
		</li>
	</ul>
	<div class="clearfix"></div>
	<ul class="list-group me-sub-test-list me-files clearfix">
<?php if (count($listFiles) > 0) {
	foreach ($listFiles as $mk => $file) { ?>
		<a class="list-group-item media me-file <?php if ($file['compile_stt'] && $file['submit'] == 1) echo ' tc-'.$file['compile_stt'] ?>" data-sid="<?php echo $file['filename'] ?>" data-key="<?php echo $mk ?>" data-id="<?php echo $file['filename'] ?>" data-lang="<?php echo $file['ext'] ?>" data-mode="<?php echo $file['mode'] ?>" data-submit="<?php echo $file['submit'] ?>" data-stt="<?php echo $file['compile_stt'] ?>">
<!--			<img style="margin:2px 4px -1px 0" class="media-object left" src="<?php echo ASSETS ?>/dist/img/<?php echo $file['ext'] ?>.png" width="12" height="12">  -->
			<span class="gensmall">[<?php echo $file['ext'] ?>]</span> <?php echo end(explode('/', $file['dir'])) ?>
			<div class="clearfix"></div>
		</a>
<?php }
} ?>
	</ul>
</div>
			<div id="code-editor-area" class="col-lg-10 no-padding">
				<div id="me-sub-code-area" class="aceditor"><?php //echo htmlentities($mySubmit->codeContent) ?></div>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="code-tool nav-tabs-custom">
		</div><!-- nav-tabs-custom -->
	</div> <!-- code-sub-area -->
</form>

<div class="alert alert-info tips-reload" style="position:fixed;right:20px;bottom:80px;width:450px;margin:0"><b>Note</b> If coding area is empty, <a href="#" onclick="location.reload();return false">reloading the page</a> shall help!</div>

<script>var codeAreaID = 'me-sub-code-area';</script>
