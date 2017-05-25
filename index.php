<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

// config file
include_once 'config/config.php';
$config = new Config();
//$userAgent = new userAgent();

/*if ($config->u) {
//	$config->u = $_SESSION['user_id'];
	$config->me = $config->getUserInfo();
}
*/
// include object files
//include_once 'objects/page.php';

if (check($__page, '?') > 0) $__page = $__page.'&';
else $__page = $__page;


$__pageAr = array_values(array_filter(explode('/', explode('?', rtrim($__page))[0])));
//$__pageAr = array_filter(explode('/', explode('&', rtrim($__page))[0]));
//print_r($__pageAr);
if ($__pageAr) {
	$page = $__pageAr[0];
	$n = (array_key_exists(1, $__pageAr) && $__pageAr[1]) ? $__pageAr[1] : null;
	$requestAr = explode('?', $__page);
//	print_r($requestAr);
//	echo $requestAr[1].'~~~';
	$config->request = isset($requestAr[1]) ? $requestAr[1] : null;
} else $config->request = explode('?', $__page)[1];

//$do = isset($_GET['do']) ? $_GET['do'] : null;

$v = $config->get('v');
$temp = $config->get('temp');
$type = $config->get('type');
$do = $config->get('do');


if ($do) header('Content-Type: text/plain; charset=utf-8');
else header('Content-Type: text/html; charset=utf-8');

if (!isset($page) || !$page) $page = 'index';

//$page_ = $page;

if ($page == 'p') $page = 'problems';
if ($page == 'w') $page = 'web';
if ($page == 'h') $page = 'hack';
if ($page == 'j') $page = 'java';

if ($page == 'b') $page = 'forum';  	// blog or forum
if ($page == 'f') $page = 'files';
if ($page == 't') $page = 'test';
if ($page == 'u') $page = 'user';
if ($page == 'c') $page = 'contest';

$allowAr = array('about', 'source', 'error', 'logout');

if (!$config->u && !in_array($page, $allowAr)) $page = 'login';
if (!file_exists('pages/'.$page.'.php')) $page = 'error';

$page_title = 'mCode';

if ($page) {
//	if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';
	include 'pages/'.$page.'.php';
	if ($temp) include 'pages/views/_temp/'.$page.'/'.$temp.'.php';
	if (!$do && !$v && !$temp) include 'pages/views/_temp/footer.php';
}
