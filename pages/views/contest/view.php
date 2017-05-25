<?php
	// set page headers
//	$page_title = "Read Products";
	$page_title = $contest->title;
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';

	echo '<div class="c-view">
		<div class="col-lg-9 c-main no-padding-left">';
	if ($m) {
		if ($m == 'discussions') {
			if ($mode == 'new') include 'pages/views/_temp/'.$page.'/view.discussions.new.php';
			else if ($did) include 'pages/views/_temp/'.$page.'/view.discussions.one.php';
			else include 'pages/views/_temp/'.$page.'/view.discussions.php';
		} else include 'pages/views/_temp/'.$page.'/view.problem.php';
	} else include 'pages/views/_temp/'.$page.'/view.home.php';
	if ($timeOut) echo '<div class="clearfix"></div>
		<div class="alerts alert-info alert-contest-passed">
			This contest has passed. You can practice this problem <a href="'.$config->pLink.'/'.$problem->code.'">here</a>
		</div>';
	echo '</div>';

	include 'pages/views/_temp/'.$page.'/view.sidebar.php';

	echo '<div class="clearfix"></div>
	</div>';
	
	// show alert who you are
	if ($timeOut == false) {
		if ($contest->uid) {
			if ($contest->team) echo '<div class="alert alert-info">You are joining with team account <a href="'.$team->link.'">'.$team->title.'</a></div>';
			else echo '<div class="alert alert-info">You are joining with your personal account <a href="'.$config->me['link'].'">'.$config->me['name'].'</a></div>';
		} else echo '<div class="alert alert-warning">You need to join with an account to start submitting this test.</div>';
	}
	
	if ($m == 'discussions') {
		$config->addJS('plugins', 'DataTables/datatables.min.js');
		if ($did) {
			$config->addJS('dist', 'contests/discussions.view.js');
			$config->addJS('dist', 'prettifier.js');
		} else $config->addJS('dist', 'contests/discussions.js');
	} else if ($m) {
		if ($timeOut == true) {
			if ($problem->id) {
				$config->addJS('dist', 'prettifier.js');
				$config->addJS('dist', 'contests/pview_timeout.js');
			}
		} else if ($contest->uid) {
			$config->addJS('plugins', 'ace/src/ace.js');
			$config->addJS('plugins', 'ace/src/ext-language_tools.js');
			$config->addJS('dist', 'beautify.js');
			$config->addJS('dist', 'contests/pview.js');
		}
	}

	if ($timeOut == false) $config->addJS('dist', 'contests/view.js');

//	include 'pages/views/_temp/footer.php';
