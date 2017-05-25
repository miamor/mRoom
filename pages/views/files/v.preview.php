<?php 
$files->dir = $config->get('dir');
$fO = $files->readOne();
extract($fO);

echo '<div class="f-preview">';
include 'pages/views/_temp/files/p.'.$fType.'.php';
echo '</div>';
