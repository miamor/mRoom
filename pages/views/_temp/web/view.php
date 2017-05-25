<div class="files-saved col-lg-2 no-padding">
	<h4>Your files</h4>
	<ul class="files-saved-more right">
		<li class="dropdown pull-right">
			<a class="dropdown-toggle" id="new-file" data-toggle="dropdown" href="#" aria-expanded="false" title="Add new submission"><span class="fa fa-plus"></span></a>
			<ul class="dropdown-menu new-file">
				<li role="presentation"><a role="menuitem" tabindex="-1" id="dir" href="#">New folder</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" id="file" href="#">New file</a></li>
			</ul>
		</li>
	</ul>
	<ul class="list-group me-web-list me-files clearfix">
<?php $W->showFiles() ?>
	</ul>
</div>

<div id="code-editor-area" class="col-lg-10 no-padding">
	<div id="me-sub-code-area" class="aceditor"><?php //echo htmlentities($mySubmit->codeContent) ?></div>
</div>

<div class="clearfix"></div>
<ul class="custom-menu">
	<li data-action="preview"><i class="fa fa-eye"></i> Preview</li>
	<li class="separator"></li>
	<li data-action="permissions"><i class="fa fa-eye"></i> Set permissions</li>
	<li data-action="delete"><i class="fa fa-gear"></i> Delete</li>
</ul>
