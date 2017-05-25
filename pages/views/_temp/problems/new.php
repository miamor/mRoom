<!-- HTML form for creating a product -->
<form action="?do=new" class="bootstrap-validator-forms new-problem" method="POST" enctype="multipart/form-data">
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
			<div class="col-lg-3 control-label no-padding">Description</div>
			<div class="col-lg-9 no-padding-right"><textarea name="content"></textarea></div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-lg-4 b-info no-padding-right">
		<h4>More</h4>
		<div class="form-group">
			<div class="col-lg-3 control-label no-padding">Languages</div>
			<div class="col-lg-9 no-padding-right">
				<select name="lang[]" multiple class="form-control chosen-select">
					<option value="cpp" selected>C++</option>
					<option value="c">C</option>
					<option value="java">Java</option>
					<option disabled value="python">Python</option>
				</select>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<div class="col-lg-3 control-label no-padding">Type</div>
			<div class="col-lg-9 no-padding-right">
				<label class="radio">
					<input type="radio" value="0" checked name="type"/> Normal
				</label>
				<label class="radio">
					<input type="radio" value="1"  name="type"/> For test
				</label>
				<label class="radio">
					<input type="radio" value="-1" name="type"/> Hidden
				</label>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="alerts alert-info">
			Problem color and link will be automatically generated, you can't modify these fields.
		</div>
	</div>
	
	<div class="clearfix"></div>

	<div class="dividers"></div>
	
	<div class="more" style="margin:20px 0">
		<div class="col-lg-5 no-padding-left">
			<h4>Extended</h4>
			<div class="form-group">
				<div class="col-lg-4 control-label no-padding">Input type</div>
				<div class="col-lg-8 no-padding-right">
					<select class="form-control" name="in_type">
						<option selected value="standard">Standard</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<div class="col-lg-4 control-label no-padding">Output type</div>
				<div class="col-lg-8 no-padding-right">
					<select class="form-control" name="out_type">
						<option selected value="standard">Standard</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<div class="col-lg-4 control-label no-padding">Score type</div>
				<div class="col-lg-8 no-padding-right">
					<select class="form-control" name="score_type">
						<option selected value="ACM">ACM</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<div class="col-lg-4 control-label no-padding">Time limit</div>
				<div class="col-lg-8 no-padding-right">
					<input type="number" min="0" max="100" value="1" name="time_limit" class="form-control left" style="width:80%!important"/> 
					<div class="control-label left">&nbsp; secs</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<div class="col-lg-4 control-label no-padding">Memory limit</div>
				<div class="col-lg-8 no-padding-right">
					<input type="number" min="10" max="10000" value="256" name="memory_limit" class="form-control left" style="width:80%!important"/> 
					<div class="control-label left">&nbsp; MB</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		
		<div class="col-lg-7 no-padding-right">
			<h4>Test cases</h4>
			<div class="form-group nav-tabs-custom">
				<ul class="nav nav_tabs border-bottom">
					<li class="active"><a href="#t1" data-toggle="tab">Upload available testcases</a></li>
					<li><a href="#t2" data-toggle="tab">Generate output with standard code (generate testcases)</a></li>
				</ul>
				<div id="upload" class="tab-content" action="?do=upload&type=testcases">
					<div id="t1" class="tab-pane active">
						<div class="callout callout-info"><b>Available testcase</b> accepted format is <b>.zip</b>, <b>.tar</b>, <b>.gz</b> folder:
							<ul>
								<li>Contains <b>n</b> folder, numbering from <b>1</b> to <b>n</b></li>
								<li>Each folder contains <b>2</b> file, <b>test.in.txt</b> and <b>test.out.txt</b></li>
							</ul>
							<b>* Note</b> Folder named <b>1</b> will be set as sample output.
						</div>
					</div> <!-- #t1 -->

					<div id="t2" class="hide tab-pane">
						<div class="callout callout-info">To <b>generate testcases</b>, upload a <b>.zip</b>, <b>.tar</b>, <b>.gz</b> file:
							<ul>
								<li>Contains <b>1</b> standard code file to generate correct output, named <b>standard.<i>[ext]</i></b> <i>(eg. standard.cpp)</i></li>
								<li>And choose one of these 2 methods:
							<ol>
								<li>Contains <b>n</b> folder, numbering from <b>1</b> to <b>n</b>.<br/>
								Each folder contains <b>1</b> file, <b>test.in.txt</b><br/>
								<b>* Note</b> Folder named <b>1</b> will be set as sample output.</li>
								
								<li>Or contains <b>1</b> file, named <b>input.txt</b>, containing test inputs, each input is separated with <b>#!end!#</b>. <br/>
								Eg. <pre><code>1 2 3<br/>#!end!#<br/>4 5 6<br/>#!end!#<br/>7 8 9</code></pre>
								<b>* Note</b> First testcase will be set as sample output.</li>
							</ol></li>
							</ul>
						</div>
					</div> <!-- #t2 -->

						<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
						<input type="file" id="fileselects" name="fileselect" />
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>


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
