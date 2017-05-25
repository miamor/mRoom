<?php
// set page headers
$page_title = $title;

if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'prettifier.js');
$config->addJS('dist', 'blog/view.js');

$stmt = $topic->getRep();
$repList = $topic->repList;
$numRep = $stmt->rowCount();

include_once 'pages/views/_temp/forum/view.php';
