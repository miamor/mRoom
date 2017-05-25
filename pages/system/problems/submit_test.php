<?php
include 'compile.php';

//echo $mySubmit->_file.'~~~'.$mySubmit->lang.'~~~'.$mySubmit->uid.'~~~'.$mySubmit->console;
//print_r($mySubmit->console);

//	$mySubmit->iid = $id;
//	$mySubmit->uid = (strpos($uid, 't') !== false) ? $uid : 't'.$uid;
	$mySubmit->uid = $uid;
	$mySubmit->team = $team;
	if (strpos($uid, 't') !== false) {
		$mySubmit->uid = explode('t', $uid);
		$mySubmit->team = 1;
	}
	$mySubmit->lang = $_fLang;
	$mySubmit->_file = $_file = $_fdir.'/'.$_fNum.'.'.$_fLang;
	$mySubmit->console = $console;

$_ar['compile'] = $console;
if ($mySubmit->checkSubmit($mySubmit->_file) <= 0) {
	$submit = $mySubmit->submit();
	if ($submit) $_ar['submit'] = array('status' => 'success', 'content' => 'Your code has been submitted successfully.');
	else $_ar['submit'] = array('status' => 'error', 'content' => 'Oops! Something went wrong with the system. Please contact the administrator for furthur help');
} else $_ar['submit'] = array('status' => 'error', 'content' => 'You\'ve already submitted this file.');

echo json_encode($_ar);
