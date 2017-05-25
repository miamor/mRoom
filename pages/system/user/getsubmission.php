<?php
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
if ($sid) {
	$mySubmit->id = $sid;
	$mySubmit->readOne();
	echo json_encode($mySubmit->data);
}
?>