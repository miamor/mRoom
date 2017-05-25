<?php
// include object files
include_once 'objects/problem.php';
include_once 'objects/submission.php';
include_once 'objects/category.php';

// prepare product object
$problem = new Problem();
$category = new Category();

// get ID of the product to be edited
//$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$id = isset($n) ? $n : null;
$page_mode = ($config->get('mode') !== null) ? $config->get('mode') : null;
$isSubPage = (isset($__pageAr[2])) ? true : false;

if (isset($id) && $id && $id != 'new') {
	$sid = ($config->get('sid') != null) ? $config->get('sid') : null;

	// set ID property of product
	$problem->id = $id;

	// read the details of product
	$problemView = $problem->readOne();
	//extract($problemView);

if ($problemView['id']) {
	// Reset $id to ID property of product
	$id = $problem->id;

	$mySubmit = new Submission();
	$mySubmit->iid = $id;

	$from_record_num = 0;
	$records_per_page = 5;

	if ($sid) {
		$mySubmit->id = $sid;
		$mySubmit->readOne();
	} else $stmt = $mySubmit->readAllMy($from_record_num, $records_per_page);

	$mySubmit->filesAr = $listFiles = $mySubmit->listFiles($config->u);

	// Create new file if nothing found!
	if (count($listFiles) <= 0) {
		$mySubmit->_fLang = $problem->lang[0];
		$mySubmit->_fNum = 0;
		$mySubmit->newFile();
	}
} else $problem->id = null;

}

if ($do) include 'system/'.$page.'/'.$do.'.php';
else {
	if ($page_mode == 'new') include 'views/'.$page.'/new.php';
	else if (!isset($id) || !$id) include 'views/'.$page.'/list.php';
	else if ($isSubPage) include 'views/'.$page.'/submissions.php';
	else {
		if ($problem->id) {
			if ($page_mode) include 'views/'.$page.'/'.$page_mode.'.php';
			else include 'views/'.$page.'/view.php';
		} else include 'error.php';
	}
}
