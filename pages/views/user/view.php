<?php
// set page headers
$page_title = $user->name;
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

echo '<div class="u-view">
	<div class="col-lg-9 u-main no-padding-left">';
	
	include 'pages/views/_temp/'.$page.'/view.home.php';

echo '</div>';

include 'pages/views/_temp/'.$page.'/view.sidebar.php';

echo '<div class="clearfix"></div>
	</div>';
	
$config->addJS('dist', 'user/view.js');
