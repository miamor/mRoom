<?php
header('Content-Type: text/plain; charset=utf-8');

$contest->replycontent = $content = isset($_POST['content']) ? $_POST['content'] : null;

if ($content) {
	$reply = $contest->addDiscussionsReply();
	if ($reply) echo '[type]success[/type][content]Comment submitted successfully.[/content]';
	else echo '[type]error[/type][content]Oops! Something went wrong with our system. Please contact the administrators for furthur help.[/content]';
} else echo '[type]error[/type][content]Missing parameters[/content]';
