<?php
// set page headers
$page_title = "Files";

include_once 'objects/files.php';

$files = new Files();
$dir = $files->dir = $config->get('dir');
$dDir = $files->rDir = MAIN_PATH.'/_fileSharing';
$fullDir = $files->fullDir = $dDir.'/'.$dir;
$filesAr = $files->readFiles();
//$rdir = explode('/', $dir)[0];
//echo '<script>var rdir = "'.$rdir.'"</script>';

if ($do) include 'system/'.$page.'/'.$do.'.php';
else if ($v) include 'views/'.$page.'/v.'.$v.'.php';
else if (!$temp) {

	$config->addJS('dist', 'files/list.js');

	include 'views/'.$page.'/list.php';

}
