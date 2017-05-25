<?php 
$dir = $config->get('dir');
$filesAr = $files->readFiles($dir);
echo json_encode($filesAr);
