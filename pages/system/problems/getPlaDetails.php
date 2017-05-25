<?php
include_once 'class/class.plagiarism.php';
$plagiarism = new Plagiarism();

$ext = isset($_POST['ext']) ? $_POST['ext'] : null;
$allowExt = array('cpp', 'c');
if (in_array($ext, $allowExt)) { // accepts c_cpp only
	$cont1 = isset($_POST['cont1']) ? $_POST['cont1'] : null;
	$cont2 = isset($_POST['cont2']) ? $_POST['cont2'] : null;
	$u1 = isset($_POST['u1']) ? $_POST['u1'] : null;
	$u2 = isset($_POST['u2']) ? $_POST['u2'] : null;
//	echo $cont1.'~~~~'.$cont2;

	if ($cont1 && $cont2) {
		$plagiarism->cont = array($cont1, $cont2);
		$plagiarism->showDetection(array($u1, $u2));
	}
}
