<?php
header('Content-Type: text/html; charset=utf-8');

$contest->title = $title = isset($_POST['title']) ? $_POST['title'] : null;
if ($title) {
	$cFind = $contest->sReadOne();
	if ($cFind['id']) echo '[type]error[/type][content]One problem with this title has already existed. Please choose another title if this is different from <a href="'.$cFind['link'].'">this</a>[/content]';
	else {
		$contest->title = $title = isset($_POST['title']) ? $_POST['title'] : null;
		$contest->content = $content = isset($_POST['content']) ? $_POST['content'] : null;
		$contest->test_time = $content = isset($_POST['test_time']) ? $_POST['test_time'] : null;
		
		$ok = true;
		$problems = array();
		if (isset($_POST['problems'])) {
			foreach ($_POST['problems'] as $oL) {
				$problems[] = $oL;
				/*if ($problem->isUsed($oL)) {
					$ok = false;
					echo '[type]error[/type][content]This contest wants to add an aready-used problem. Please remove it.[/content]';
				}*/
			}
		}
		if ($ok == true) {
			$contest->problems = implode(',', $problems);
			
			if ($title && $content && count($problems) > 0) {
				$create = $contest->create();
				if ($create) {
					$cNew = $contest->contestNew;
					echo '[type]success[/type][dataID]'.$cNew['link'].'[/dataID][content]Problem created successfully. Redirecting to <a href="'.$cNew['link'].'">'.$title.'</a>...[/content]';
					echo '<script>window.location.href = "'.$cNew['link'].'"</script>';
				} else echo '[type]error[/type][content]Oops! Something went wrong with our system. Please contact the administrators for furthur help.[/content]';
			} else echo '[type]error[/type][content]Missing parameters![/content]';
		}
	}
} else echo '[type]error[/type][content]Missing parameters[/content]';
