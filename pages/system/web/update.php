<?php
$dir = isset($_POST['dir']) ? ($_POST['dir']) : null;
$content = $W->content = isset($_POST['content']) ? html_entity_decode($_POST['content']) : null;
$save = file_put_contents($dir, $content);
echo $content;
