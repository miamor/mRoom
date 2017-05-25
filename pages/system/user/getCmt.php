<?php
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
if ($sid) {
	$mySubmit->id = $sid;
	$cmtList = $mySubmit->cmtList();
	echo json_encode($cmtList);
}
?>