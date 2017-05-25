<?php
include_once 'class/class.plagiarism.php';
$plagiarism = new Plagiarism();

if (isset($_POST['fname'])) {
	$mode = $_POST['mode'];
	$_fName = $_POST['fname'];
	$mode = end(explode('.', $_fName));
} else if (isset($_POST['mode'])) $mode = $_POST['mode'];
if ($mode) {
	$_fLang = $mode;
	if ($mode == 'c_cpp') $_fLang = 'cpp';
}
//$uid = isset($_POST['uid']) ? $_POST['uid'] : $config->u;
if ($mySubmit->team) $uid = 't'.$mySubmit->tid;
else if (isset($_POST['uid'])) $uid = $_POST['uid'];
else $uid = $config->u;
$_fNum = isset($_POST['fnum']) ? $_POST['fnum'] : null;
$id = $mySubmit->iid;
$_fName = isset($_POST['fname']) ? $_POST['fname'] : ($_fNum.'.'.$_fLang);

$plagiarism->_dir = $config->codeDir.'/p'.$id;
$plagiarism->_fileFormat = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'.'.$_fName;
$plagiarism->_file = $mySubmit->codeDir.'/p'.$id.'/u'.$uid.'/'.$_fLang.'/'.$_fName;

$plagiarism->checkLocal();
// disable checkOnline
//$plagiarism->checkOnline();

$console = array();

$console['checkLocal'] = $plagiarism->checkLocal;
//$console['checkOnline'] = $plagiarism->checkOnline;
$console['checkOnline'] = array('status' => 'disabled');

echo json_encode($console);
