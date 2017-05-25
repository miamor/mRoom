<?php
// set page headers
$page_title = $user->name."'s submissions";
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

echo '<div class="u-view">
	<div class="col-lg-9 u-main no-padding-left">';
	
if ($mID) include 'pages/views/_temp/'.$page.'/view.'.$m.'.one.php';
else include 'pages/views/_temp/'.$page.'/view.'.$m.'.php';

echo '</div>';

include 'pages/views/_temp/'.$page.'/view.sidebar.php';

echo '<div class="clearfix"></div>
	</div>';
	
$config->addJS('plugins', 'DataTables/datatables.min.js');
$config->addJS('dist', 'prettifier.js');
$config->addJS('dist', 'user/'.$m.'.js');
