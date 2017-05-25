<?php
// include object files
include_once 'objects/search.php';

// prepare product object
$search = new Search();
$search->keyword = (isset($_POST['keyword'])) ? $_POST['keyword'] : ($config->get('keyword') != null) ? $config->get('keyword') : null;
if ($search->keyword) $search->search();

if ($do) include 'system/'.$page.'/'.$do.'.php';
else if ($search->keyword) include 'views/'.$page.'/search.php';
else include 'views/'.$page.'/index.php';
