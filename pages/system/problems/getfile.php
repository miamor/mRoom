<?php
	$lang = isset($_POST['lang']) ? $_POST['lang'] : '';
	$fid = isset($_POST['fid']) ? $_POST['fid'] : '';
//	$uid = isset($_POST['uid']) ? $_POST['uid'] : $config->u;
	$uid = ($mySubmit->team) ? $mySubmit->tid : $config->u;
	$isSubmit = isset($_POST['isSubmit']) ? $_POST['isSubmit'] : 0;
	$key = isset($_POST['key']) ? $_POST['key'] : '';
	
	if ($mySubmit->team) $mySubmit->file = $config->codeDir.'/p'.$mySubmit->iid.'/ut'.$uid.'/'.$lang.'/'.$fid.'.'.$lang;
	else $mySubmit->file = $config->codeDir.'/p'.$mySubmit->iid.'/u'.$uid.'/'.$lang.'/'.$fid.'.'.$lang;
	
	$file_details = $mySubmit->filesAr[$key];
	
	$codeContent = $mySubmit->getCodeContent();
	$file_details['content'] = $codeContent;
	
echo json_encode($file_details);