<?php
$page_title = "Search \"{$search->keyword}\"";
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'search/search.js');

include_once 'pages/views/_temp/'.$page.'/search.php';
