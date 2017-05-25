<?php
	$_fLang = isset($_POST['lang']) ? $_POST['lang'] : '';
	$_fNum = isset($_POST['fnum']) ? $_POST['fnum'] : '';
	if ($_fLang == 'c_cpp') $_fLang = 'cpp';
	$mySubmit->_fNum = $_fNum;
	$mySubmit->_fLang = $_fLang;
//	$mySubmit->iid = $id;
	echo $mySubmit->newFile();
