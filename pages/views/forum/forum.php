<?php
// set page headers
$page_title = "Forum";
include_once "pages/views/_temp/header.php";

// query products
$stmt = $forum->readAll();
$forumsList = $forum->forumsList;
$num = $stmt->rowCount();

$lastTopic = $topic->readAll(true, '', '', '', 10);

$activeTopic = $topic->readAll(true, '', '', 'replies DESC', 10);

$viewsTopic = $topic->readAll(true, '', '', 'views DESC', 10);

// display the products if there are any
if ($num>0) 
	include_once 'pages/views/_temp/forum/forum.php';
 else 
	echo '<div class="alert alert-info">Nothing\'s found.</div>';

