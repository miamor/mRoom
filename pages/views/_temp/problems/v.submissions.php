<?php
	// set page headers
//	$page_title = "Read Products";
	$page_title = 'Submissions of '.$problem->title;
//	include_once "pages/views/_temp/header.php";

	$mySubmit->iid = $problem->id;
	$stmt = $mySubmit->readAll();
	$subsList = $problem->getSubmissions();

	include 'pages/views/_temp/problems/submissions.php';
	

	$config->addJS('plugins', 'ace/src/ace.js');
//	$config->addJS('plugins', 'ace/src/ext-language_tools.js');
	$config->addJS('dist', 'beautify.js');
	$config->addJS('dist', 'problems/view.js');

//	include 'pages/views/_temp/footer.php';
