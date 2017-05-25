<?php if (count($mySubsList) > 0) { ?>
<form class="code-area">
	<div class="files-saved col-lg-2 no-padding">
			<h4>Your files</h4>
			<div class="clearfix"></div>
			<ul class="list-group me-sub-test-list me-files p-submissions-list clearfix">

		<?php foreach ($mySubsList as $k => $row) {
			$k++; ?>
			<a id="<?php echo $id ?>" class="list-group-item media me-file ranker rank-<?php echo $k ?> tc-<?php if ($row['compile_stt'] != 0) echo 'WA'; else if ($row['score'] == 100) echo 'AC bold'; ?>" data-id="<?php echo $row['id'] ?>">
				<div class="col-lg-9 no-padding"><?php echo end(explode('/', $row['file'])) ?></div>
				<div class="col-lg-3 no-padding-right" class="center"><?php echo $row['time_taken'] ?></div>
				<div class="clearfix"></div>
			</a>
		<?php } ?>

			</ul>
		</div>

	<div id="code-editor-area" class="p-submission-details col-lg-10 no-padding-left" style="height:100%;overflow:hidden">
		<div class="col-lg-8 no-padding" style="height:100%">
			<form class="code-area hidden">
				<input id="sub-num" name="fnum" class="hide">
				<input id="mode" name="mode" class="hide">
				<textarea id="sub-code" name="code" class="non-sce hide"></textarea>
				<textarea id="sub-code-formatted" name="code-formatted" class="non-sce hide"></textarea>
			</form>
			<div class="code-editor-area p-sub"></div>
			<div class="code-tool">
				<div class="tab-pane" id="plachecker">
					<div class="me-sub-compile" id="me-sub-plachecker"></div>
				</div><!-- #plachecker -->
			</div>
		</div> 

		<div class="col-lg-4 p-sub-score no-padding-right" style="padding-top:15px!important">
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>

</form>

<? } else echo '<div class="alerts alert-warning">No submissions found.</div>' ?>
