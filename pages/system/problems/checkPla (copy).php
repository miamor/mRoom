<?php

include_once 'class/class.Diff.php';
include_once 'class/class.plagiarism.php';
$plagiarism = new Plagiarism();

$mode = isset($_POST['mode']) ? $_POST['mode'] : null;
$_fLang = $mode;
if ($mode == 'c_cpp') $_fLang = 'cpp';
$uid = isset($_POST['uid']) ? $_POST['uid'] : $config->u;
$_fNum = isset($_POST['fnum']) ? $_POST['fnum'] : null;

$plagiarism->_dir = $config->codeDir.'/p'.$id;
$plagiarism->_fileFormat = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'.'.$_fNum.'.'.$_fLang;
$plagiarism->checkLocal();
$plagiarism->checkOnline();

$console = array();

$console['checkLocal'] = $plagiarism->checkLocal;
$console['checkOnline'] = $plagiarism->checkOnline;

echo json_encode($console);