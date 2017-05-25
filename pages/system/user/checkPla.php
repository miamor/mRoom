<?php
include_once 'class/class.plagiarism.php';
$plagiarism = new Plagiarism();

$mode = isset($_POST['mode']) ? $_POST['mode'] : null;
$_fLang = $mode;
if ($mode == 'c_cpp') $_fLang = 'cpp';
$uid = isset($_POST['uid']) ? $_POST['uid'] : $user->id;
$_fNum = isset($_POST['fnum']) ? $_POST['fnum'] : null;

$mySubmit->iid = $id = isset($_POST['iid']) ? $_POST['iid'] : null;

$_fName = isset($_POST['fname']) ? $_POST['fname'] : ($_fNum.'.'.$_fLang);

$plagiarism->_dir = $config->codeDir.'/p'.$id;
$plagiarism->_fileFormat = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'.'.$_fName;
$plagiarism->_file = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'/'.$_fLang.'/'.$_fName;
$plagiarism->checkLocal();
// disable checkOnline
$plagiarism->checkOnline();

$console = array();

$console['checkLocal'] = $plagiarism->checkLocal;
$console['checkOnline'] = $plagiarism->checkOnline;
$console['checkOnline'] = array('status' => 'disabled');

echo json_encode($console);
