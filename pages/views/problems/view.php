<?php
	// set page headers
//	$page_title = "Read Products";
	$page_title = $problem->title;
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

	include 'pages/views/_temp/problems/view.php';

	$config->addJS('plugins', 'ace/src/ace.js');
	$config->addJS('plugins', 'ace/src/mode-c_cpp.js');
	$config->addJS('plugins', 'ace/src/mode-c.js');
	$config->addJS('plugins', 'ace/src/mode-java.js');
	$config->addJS('plugins', 'ace/src/mode-javascript.js');
//	$config->addJS('plugins', 'ace/src/ext-language_tools.js');
	$config->addJS('dist', 'prettifier.js');
	$config->addJS('dist', 'beautify.js');
	$config->addJS('dist', 'problems/view.js');

//	include 'pages/views/_temp/footer.php';
