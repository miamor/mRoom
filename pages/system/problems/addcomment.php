<?php
$sid = $mySubmit->id = isset($_POST['sid']) ? $_POST['sid'] : null;
$line = isset($_POST['line']) ? $_POST['line'] : null;
$content = isset($_POST['comment-line-'.$sid.$line]) ? $_POST['comment-line-'.$sid.$line] : null;

if ($sid && $line && $content) {
	$cmtLine = $mySubmit->addCmt($line, $content);
	if ($cmtLine) echo json_encode($mySubmit->newCmt);
	else echo -1;
}
