<div class="borderwrap">
	<div class="maintitle floated dropped">
		<h3>New topic </h3>
	</div>
	<form action="?do=new" id="new-topic" class="new-topic bootstrap-validator-form maincontent" style="padding:15px 0" id="new-topic">
		<div class="form-group">
			<div class="col-lg-3 control-label no-padding-right">Title</div>
			<div class="col-lg-9"><input type="text" name="title" class="form-control" /></div>
			<div class="clearfix"></div>
		</div>

		<div class="form-group">
			<div class="col-lg-3 no-padding-right">Type</div>
			<div class="col-lg-9">
				<label class="radio col-lg-4">
					<input type="radio" value="1" name="status"/> Announcement &amp; Sticky
				</label>
				<label class="radio col-lg-4">
					<input type="radio" value="0" checked name="status"/> Normal
				</label>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="form-group">
			<div class="col-lg-3 control-label no-padding-right">Content</div>
			<div class="col-lg-9"><textarea name="content"></textarea></div>
			<div class="clearfix"></div>
		</div>

		<div class="add-form-submit center">
			<input type="reset" value="Reset" class="btn btn-default">
			<input type="submit" value="Submit" class="btn">
		</div>
	</form>
</div>
