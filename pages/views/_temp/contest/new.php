<!-- HTML form for creating a product -->
<form action="?do=new" class="bootstrap-validator-form new-contest" method="POST" enctype="multipart/form-data">
	<div class="col-lg-8 p-info no-padding-left">
		<h4>Basic information</h4>
		<div class="form-group">
			<div class="col-lg-3 control-label no-padding">Title</div>
			<div class="col-lg-9 no-padding-right">
				<input type="text" name="title" class="form-control" placeholder="Problem title"/>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<div class="col-lg-3 control-label no-padding">Test time (min)</div>
			<div class="col-lg-9 no-padding-right">
				<input type="number" name="test_time" class="form-control" placeholder="Test time"/>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="form-group">
			<div class="col-lg-3 control-label no-padding">Description</div>
			<div class="col-lg-9 no-padding-right"><textarea name="content"></textarea></div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-lg-4 b-info no-padding-right">
		<h4>Problems list</h4>
		<div class="form-group">
			<div class="col-lg-3 hidden control-label no-padding-left">Problems</div>
			<div class="col-lg-12 no-padding">
				<select name="problems[]" multiple class="form-control chosen-select">
				<optgroup label="Mine">
				<?php foreach ($probListMy as $oP) {
					echo '<option value="'.$oP['id'].'">['.$oP['code'].'] '.$oP['title'].' ('.$oP['uses'].')</option>';
				} ?>
				</optgroup>
				<optgroup label="Others'">
				<?php foreach ($probListOthers as $oP) {
					echo '<option value="'.$oP['id'].'">['.$oP['code'].'] '.$oP['title'].' ('.$oP['uses'].')</option>';
				} ?>
				</optgroup>
				</select>
			</div>
			<div class="clearfix"></div>
			<div style="text-align:right;margin-top:8px"><a target="_blank" href="<?php echo $config->pLink.'?mode=new' ?>">Create new problem</a></div>
		</div>
		<div class="alerts alert-info">
			You can select problems from problems list for contests.
		</div>
	</div>
	
	<div class="clearfix"></div>

	<div class="form-group hidden">
		<div class="col-lg-4 control-label no-padding"></div>
		<div class="col-lg-8 no-padding-right">
			<button type="submit" class="btn btn-primary">Create</button>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="add-form-submit center">
		<input type="reset" value="Reset" class="btn btn-default">
		<input type="submit" value="Submit" class="btn">
	</div>

</form>
