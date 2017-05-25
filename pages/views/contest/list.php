<?php
// set page headers
$page_title = "Contests";
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'problems/list.js');

$cList = $contest->readAll();

// display the products if there are any
include_once 'pages/views/_temp/'.$page.'/list.php';

//include_once "pages/views/_temp/footer.php";
?>
