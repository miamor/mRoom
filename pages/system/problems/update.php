<?php
//$dir = isset($_POST['dir']) ? ($_POST['dir']) : null;

$id = isset($_POST['id']) ? $_POST['id'] : null;
//$uid = isset($_POST['uid']) ? $_POST['uid'] : $config->u;
//$team = (strpos($uid, 't') !== false) ? 1 : 0;
$uid = ($mySubmit->team) ? $mySubmit->tid : $config->u;
$_fLang = isset($_POST['lang']) ? $_POST['lang'] : null;
if ($mySubmit->team) $_fdir = $config->codeDir.'/p'.$mySubmit->iid.'/ut'.$uid.'/'.$_fLang;
else $_fdir = $config->codeDir.'/p'.$mySubmit->iid.'/u'.$uid.'/'.$_fLang;
$_file = $_fdir.'/'.$id.'.'.$_fLang;
$_codeContent = isset($_POST['code']) ? $_POST['code'] : '';

$content = isset($_POST['content']) ? html_entity_decode($_POST['content']) : null;

$save = file_put_contents($_file, $content);
echo $_fdir;
