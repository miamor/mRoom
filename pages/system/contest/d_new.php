<?php
header('Content-Type: text/plain; charset=utf-8');

$contest->dTitle = $title = isset($_POST['title']) ? $_POST['title'] : null;
if ($title && $config->me['is_mod'] == 1) {
	$tp = $contest->sDiscussionsReadOne();
	if ($tp['id']) echo '[type]error[/type][content]One topic with this title has already existed. Please choose another title if this is different from <a href="'.$contest->link.'/discussions/'.$contest->did.'">this</a>[/content]';
	else {
		$contest->dTitle = $title = isset($_POST['title']) ? $_POST['title'] : null;
		$contest->dLink = encodeURL($contest->dTitle);
		$contest->dContent = $content = isset($_POST['content']) ? $_POST['content'] : null;
		if ($title && $content) {
			$create = $contest->addDiscussions();
			if ($create) {
//				$tpn = $topic->readOne();
				echo '[type]success[/type][dataID]'.$contest->link.'/discussions/'.$contest->did.'[/dataID][content]Topic created successfully. Redirecting to <a href="'.$contest->link.'/discussions/'.$contest->did.'</a>">'.$title.'</a>...[/content]';
			} else echo '[type]error[/type][content]Oops! Something went wrong with our system. Please contact the administrators for furthur help.[/content]';
		} else echo '[type]error[/type][content]Missing parameters[/content]';
	}
} else echo '[type]error[/type][content]Missing parameters[/content]';
