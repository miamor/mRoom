<?php
	// set page headers
//	$page_title = "Read Products";
	$page_title = 'Submissions of '.$problem->title;
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

	$mySubmit->iid = $problem->id;
	$stmt = $mySubmit->readAll();
	$subsList = $problem->getSubmissions();

	include 'pages/views/_temp/problems/view.php';
	
	$config->addJS('plugins', 'DataTables/datatables.min.js');
	$config->addJS('dist', 'prettifier.js');
	$config->addJS('dist', 'problems/submissions.js');

//	include 'pages/views/_temp/footer.php';
