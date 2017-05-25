<?php

	$all = isset($_POST['all']) ? $_POST['all'] : '';
	$_codeContent = isset($_POST['code']) ? $_POST['code'] : '';
if (isset($_POST['code'])) {
//	echo $_POST['code'].'~~~~~~~~~~';
	$_codeFContent = isset($_POST['code-formatted']) ? strip_comments($_POST['code-formatted']) : ''; 

	$mode = isset($_POST['mode']) ? $_POST['mode'] : null;
	$_fLang = $mode;
	if ($mode == 'c_cpp') $_fLang = 'cpp';
//	$uid = isset($_POST['uid']) ? $_POST['uid'] : $config->u;
//	$team = (strpos($uid, 't') !== false) ? 1 : 0;
//	$uTCheck = (strpos($uid, 't') !== false) ? explode('t', $uid)[1] : $uid;
	$uid = ($mySubmit->team) ? $mySubmit->tid : $config->u;
	$_fNum = isset($_POST['fnum']) ? $_POST['fnum'] : null;

	$pid = isset($_POST['pid']) ? $_POST['pid'] : $mySubmit->iid;
	if ($pid) $id = $pid;

	if ($mySubmit->team) $_fdir = $config->codeDir.'/p'.$id.'/ut'.$uid.'/'.$_fLang;
	else $_fdir = $config->codeDir.'/p'.$id.'/u'.$uid.'/'.$_fLang;
	
/*	$_file = $_fdir.'/'.$_fLang.'_'.$_fNum.'.'.$_fLang;
	$_fileFormat = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'.'.$_fLang.'_'.$_fNum.'.'.$_fLang;
*/	$_file = $_fdir.'/'.$_fNum.'.'.$_fLang;
	$_fileFormat = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'.'.$_fNum.'.'.$_fLang;

	file_put_contents($_file, $_codeContent);
	file_put_contents($_fileFormat, $_codeFContent);
	
	include_once 'class/class.Diff.php';
	include_once 'class/class.compile.php';

	$compile = new Compile();

	$compile->_dir = $config->codeDir.'/p'.$id;
	$compile->_file = $_file;
	$compile->_codeFContent = $_codeFContent;
	
	$mySubmit->_file = $_file;
	if ($do == 'submit') $all = true;
	else if ($mySubmit->checkSubmit() > 0) $all = true;
	else $all = false;
	$compile->compile($all);

	$console = $compile->console;

	if ($mode == null || $_fNum == null) $console = array('status' => 'error', 'content' => 'Missing paramemters');
	else if (!$console) $console = array('status' => 'error', 'content' => 'Something went wrong');
	else if ($console['status'] == 'success') {
//		$console['content'] = $_fileFormat;
	}
} else $console = array('status' => 'error', 'content' => 'You can\'t compile a blank file.');

if ($do == 'compile') echo json_encode($console);
