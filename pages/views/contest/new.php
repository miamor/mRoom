<?php
	// set page headers
	$page_title = "New problem";
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

	$probListMy = $problem->readForTest();
	$probListOthers = $problem->readForTest(-1);
	
	include 'pages/views/_temp/contest/new.php';

//	$config->addJS('plugins', 'fileupload/jquery.fileupload.js');
//	$config->addJS('dist', 'problems/new.js');

//	include 'pages/views/_temp/footer.php';
