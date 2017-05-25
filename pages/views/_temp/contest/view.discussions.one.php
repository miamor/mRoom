<div class="d-view">
<h3 style="margin:0 0 20px"><?php echo $dc['title'] ?></h3>
<div class="maincontent" id="tfirst">
	<div valign="top" class="col-lg-2 no-padding">
		<a title="<?php echo $dc['author']['name'] ?>" href="<?php echo $dc['author']['link'] ?>" class="postprofile-avatar" data-online="<?php echo $dc['author']['online'] ?>">
			<img class="avatar" style="width:100%" src="<?php echo $dc['author']['avatar'] ?>">
		</a>
        <div class="postprofile-name text-center">
            <a title="<?php echo $dc['author']['name'] ?>" href="<?php echo $dc['author']['link'] ?>"><?php echo $dc['author']['name'] ?></a>
        </div>
	</div>
	<div valign="top" class="col-lg-10 no-padding-right">
        <div class="dc-content-wrapper">
            <div class="dc-content"><?php echo $dc['content'] ?></div>
            <div class="dc-more">
                <div class="dc-time right gensmall"><span class="fa fa-clock-o"></span> <?php echo $dc['created'] ?></div>
            </div>
            <div class="clearfix"></div>
        </div>
	</div>
	<div class="clearfix"></div>
</div>


<div class="borderwrap treplies tbmargin">
	<div class="maintitle hidden floated dropped">
		<h3>Replies</h3>
	</div>
	<div class="maincontent t_replies">
	<table width="100%" cellspacing="0" cellpadding="0" class="ipbtable" id="treplies">
		<thead class="hidden">
			<tr>
				<th class="th-none hidden"></th>
				<th class="th-none hidden"></th>
			</tr>
		</thead>
	</table>
	</div>
</div>


<div class="clearfix"></div>


<div class="borderwrap">
	<div class="maintitle floated dropped">
		<h3><i class="fa fa-comments"></i> Write reply </h3>
	</div>
	<form class="maincontent bootstrap-validator-form" action="?do=reply" id="treply">
		<div style="padding:10px 0">
			<textarea style="height:200px" name="content"></textarea>
			<div class="add-form-submit center" style="margin-top:10px">
				<input type="reset" value="Reset" class="btn btn-default"/>
				<input type="submit" value="Submit" class="btn btn-success"/>
			</div>
		</div>
	</form>
</div>

<style>
.linenums li{position:relative;border-left:3px solid #EBEFF9;padding:0 0 4px 5px;line-height:20px}
ol.linenums li:hover{background:#EBEFF9}
.linenums{margin:0}
.code_content pre{max-height:300px;overflow:auto}
</style>
</div>