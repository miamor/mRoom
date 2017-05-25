<?php
// include object files
include_once 'objects/user.php';
include_once 'objects/submission.php';

// prepare product object
$user = new User();

// get ID of the product to be edited
//$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$id = isset($n) ? $n : '';
$m = (isset($__pageAr[2])) ? $__pageAr[2] : null;
if (isset($id) && $id) {
	// set ID property of product
	$user->id = $id;

	// read the details of product
	$uView = $user->readOne();
	extract($uView);

	// Reset $id to ID property of product
	$id = $user->id;

	$mySubmit = new Submission();
	$mySubmit->uid = $id;

	$mID = (isset($__pageAr[3])) ? $__pageAr[3] : null;

	if ($m == 'submissions') { // view users' (something) (like submissions, blogs,...)
		if ($mID) {
			$mySubmit->id = $mID;
			$mView = $mySubmit->readOne();
			$mID = $mView['id'];
		} else $mList = $mySubmit->readAll($id);
	} else if ($m == 'blogs') {
		include_once 'objects/topic.php';
		$topic = new Topic();
		if (!$mID) {
			$mList = $topic->readAll(true, '', $id);
		}
	} else if ($m == 'teams') {
		include_once 'objects/team.php';
		$team = new Team();
		if (!$mID) {
			$mList = $team->readAllMy($id);
		}
	}
}

if ($do) include 'system/'.$page.'/'.$do.'.php';
else {
	if (!isset($id) || !$id) include 'views/'.$page.'/list.php';
	else if ($m) include 'views/'.$page.'/v.'.$m.'.php';
	else include 'views/'.$page.'/view.php';
}
