</div> <!-- #main-content -->
	<div class="footer">
		<div class="col-lg-5 no-padding-left" style="text-align:left">
			<a href="<?php echo MAIN_URL ?>/about">About</a>
			 • 
			<a href="<?php echo MAIN_URL ?>/links/plagiarism">Plagiarism checker</a>
			 • 
		</div>
		<div class="col-lg-2 no-padding" style="text-align:center">
			<div class="logo-foot"><span class="fa fa-code"></span></div>
		</div>
		<div class="col-lg-5 no-padding-right" style="text-align:right">
			<a href="<?php echo MAIN_URL ?>/links/plagiarism">Terms of use</a>
			 • 
			<a href="<?php echo MAIN_URL ?>/links/plagiarism">Help</a>
			 • 
			<a href="<?php echo MAIN_URL ?>/links/plagiarism">Contact us</a>
		</div>
		<div class="clearfix"></div>
	</div>
	
<?php /*if ($config->u) { ?>
	<div id="right-side" class="col-lg-2">
		<? include 'pages/chat.php'; ?>
	<? if ($config->u) { ?>
		<div class="right-bottom">
			<a href="<? echo MAIN_URL ?>/logout"><span class="fa fa-sign-out"></span> Log out</a> (@<? echo $config->me['username'] ?>)
		</div>
	<? } ?>
	</div>
<? }*/ ?>

<div class="popup hide"><div class="popup-inner">
	<div class="popup-content hide">
		<a class="popup-btn" role="close"></a>
		<div class="the-board"></div>
	</div>
</div></div>
<!--	<link rel="stylesheet" href="<? echo ASSETS ?>/plugins/sceditor/minified/themes/default.min.css" type="text/css" media="all" />
	<script type="text/javascript" src="<? echo JS ?>/icons.js"></script> -->
	<?php $config->echoJS() ?>

</body>
</html>
