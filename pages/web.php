<?php
// include object files
include_once 'objects/web.php';

$W = new Web();

if (isset($id) && $id && $id != 'new') {
	$sid = ($config->get('sid') != null) ? $config->get('sid') : null;
}

if ($do) include 'system/'.$page.'/'.$do.'.php';
else include 'views/'.$page.'/view.php';

