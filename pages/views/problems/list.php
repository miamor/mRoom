<?php
// set page headers
$page_title = "Problems";
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'problems/list.js');

$stmt = $problem->readAll();
$problemsList = $problem->problemsList;
$num = $stmt->rowCount();

$stmt = $category->read();
$catList = $category->catList;

// display the products if there are any
include_once 'pages/views/_temp/'.$page.'/list.php';

//include_once "pages/views/_temp/footer.php";
?>
