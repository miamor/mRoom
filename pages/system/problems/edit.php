<?php
header('Content-Type: text/html; charset=utf-8');
include MAIN_PATH.'/class/class.scompile.php';

$title = isset($_POST['title']) ? $_POST['title'] : null;
if ($title) {
	if ($problem->id) {
		$problem->title = $title = isset($_POST['title']) ? $_POST['title'] : null;
		$problem->content = $content = isset($_POST['content']) ? $_POST['content'] : null;
		
		$lang = array();
		if (isset($_POST['lang'])) {
			foreach ($_POST['lang'] as $oL) {
				$lang[] = $oL;
			}
		}
		$problem->lang = $langStr = implode('|', $lang);
//		$problem->stt = $stt = isset($_POST['type']) ? $_POST['type'] : null;
		
		$problem->score_type = $score_type = isset($_POST['score_type']) ? $_POST['score_type'] : null;
		$problem->time_limit = $time_limit = isset($_POST['time_limit']) ? $_POST['time_limit'] : 0;
		$problem->memory_limit = $memory_limit = isset($_POST['memory_limit']) ? $_POST['memory_limit'] : null;
		$problem->in_type = $in_type = isset($_POST['in_type']) ? $_POST['in_type'] : null;
		$problem->out_type = $out_type = isset($_POST['out_type']) ? $_POST['out_type'] : null;
		$problem->upfile = $upfile = isset($_FILES['fileselect']) ? $_FILES['fileselect'] : null;
		$problem->maxSize = $maxSize = 1000000;
		$problem->in_num = isset($_POST['in_num']) ? $_POST['in_num'] : null;
		$problem->in_standard = isset($_POST['in_standard']) ? $_POST['in_standard'] : null;

		if ($title && $content && /*$cid &&*/ $score_type && $langStr && $memory_limit && $in_type && $out_type/* && $upfile*/) {
			$edit = $problem->edit();
			if ($edit) {
				echo '<script>window.location.href = "'.$problem->link.'"</script>';
			} else echo '[type]error[/type][content]Oops! Something went wrong with our system. Please contact the administrators for furthur help.[/content]';
		} else echo '[type]error[/type][content]Missing parameters![/content]';
	}
} else echo '[type]error[/type][content]Missing parameters[/content]';
