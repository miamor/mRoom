<?php
$action = isset($_POST['action']) ? $_POST['action'] : $config->get('action');

if ($action == 'hide') {
	$valAr = ($problem->stt == -1) ? array('stt' => 0) : array('stt' => -1);
} else if ($action == 'test') {
	$valAr = array('stt' => 1);
}

if ($problem->update($valAr)) echo 1;
else echo 0;
