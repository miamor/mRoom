<?php
	// set page headers
	$page_title = 'Web development';
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

//	$listFiles = $W->listFiles($config->u);

	include 'pages/views/_temp/web/view.php';

	$config->addJS('plugins', 'ace/src/ace.js');
	$config->addJS('plugins', 'ace/src/ext-language_tools.js');
//	$config->addJS('dist', 'prettifier.js');
//	$config->addJS('dist', 'beautify.js');
	$config->addJS('dist', 'web/view.js');
    $config->addJS('plugins', 'contextmenu/jquery.contextmenu.js');
	echo '<link rel="stylesheet" href="'.PLUGINS.'/contextmenu/jquery.contextmenu.css">';

//	include 'pages/views/_temp/footer.php';
