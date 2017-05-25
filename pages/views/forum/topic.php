<?php
// set page headers
$page_title = $fInfo['title'];
include_once "pages/views/_temp/header.php";

$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'forum/topic.js');

$topic->readAll();
$topicsList = $topic->topicsList;
$num = count($topicsList);

// display the products if there are any
include_once 'pages/views/_temp/forum/topic.php';
