<?php
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
if ($sid) {
	$mySubmit->id = $sid;
	$mySubmit->tests = $problem->tests;
	$mySubmit->readOne();
	echo json_encode($mySubmit->data);
}