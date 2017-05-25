<?php
header('Content-Type: text/html; charset=utf-8');

$problem->title = $title = isset($_POST['title']) ? $_POST['title'] : null;
if ($title) {
	$prob = $problem->readOne();
	if ($prob['id']) echo '[type]error[/type][content]One problem with this title has already existed. Please choose another title if this is different from <a href="'.$prob->link.'">this</a>[/content]';
	else {
		$problem->title = $title = isset($_POST['title']) ? $_POST['title'] : null;
		$problem->content = $content = isset($_POST['content']) ? $_POST['content'] : null;
		$problem->cid = $cid = isset($_POST['cid']) ? $_POST['cid'] : null;
		$problem->score_type = $score_type = isset($_POST['score_type']) ? $_POST['score_type'] : null;
		$problem->time_limit = $time_limit = isset($_POST['time_limit']) ? $_POST['time_limit'] : 0;
		$problem->memory_limit = $memory_limit = isset($_POST['memory_limit']) ? $_POST['memory_limit'] : null;
		$problem->in_type = $in_type = isset($_POST['in_type']) ? $_POST['in_type'] : null;
		$problem->out_type = $out_type = isset($_POST['out_type']) ? $_POST['out_type'] : null;
		$problem->upfile = $upfile = isset($_FILES['fileselect']) ? $_FILES['fileselect'] : null;
		$problem->maxSize = $maxSize = 1000000;
		if ($title && $content && /*$cid &&*/ $score_type && $memory_limit && $in_type && $out_type && $upfile) {
			$create = $problem->create();
			$prob = $problem->problemNew;
			if ($create) {
				echo '[type]success[/type][dataID]'.$prob->link.'[/dataID][content]Problem created successfully. Redirecting to <a href="'.$prob->link.'">'.$title.'</a>...[/content]';
				echo '<script>window.location.href = "'.$prob->link.'"</script>';
			} else echo '[type]error[/type][content]Oops! Something went wrong with our system. Please contact the administrators for furthur help.[/content]';
		} else echo '[type]error[/type][content]'.$upfile.'~~Missing parameters[/content]';
	}
} else echo '[type]error[/type][content]Missing parameters[/content]';
