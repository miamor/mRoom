<?php 
// include object files
include_once 'objects/forum.php';
include_once 'objects/topic.php';

// prepare product object
$forum = new Forum();
$topic = new Topic();

// get ID of the product to be edited
//$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

//$f = ($config->get('f') !== null) ? $config->get('f') : null;
$mode = ($config->get('mode') !== null) ? $config->get('mode') : null;
$f = isset($n) ? $n : null;
$id = ($__pageAr[2] !== null) ? $__pageAr[2] : null;

if ($f) {
	$forum->id = $f;

	$fInfo = $forum->readOne();
	$forum->child = $forum->getChild();
}

$topic->fid = $forum->id = $fInfo['id'];
$topic->forumLink = $topic->f = $forum->link;
$topic->forums = array_merge(array($forum->id), $forum->child);

if ($id) {
	$topic->id = $id;
	$id = null;
	$topicView = $topic->readOne();
	if ($topicView['fid'] == $topic->fid) {
		extract($topicView);
		// Reset $id to ID property of product
		$topic->id = $id;
		$topic->link = $link;
	}
}


if ($do) include 'system/'.$page.'/'.$do.'.php';
else if (!$temp) {
	if ($id) {
		if ($mode == 'edit') include 'views/'.$page.'/'.$mode.'.php';
		else {
			$topic->updateView();
			include 'views/'.$page.'/view.php';
		}
	} else if ($mode == 'new') include 'views/'.$page.'/new.php';
	else if ($f) include 'views/'.$page.'/topic.php';
	else include 'views/'.$page.'/forum.php';
}
