<?php
// include object files
include_once 'objects/problem.php';
include_once 'objects/submission.php';
include_once 'objects/contest.php';
include_once 'objects/team.php';

// prepare product object
$problem = new Problem();
$contest = new Contest();
$team = new Team();
$mySubmit = new Submission();

// get ID of the product to be edited
//$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$id = isset($n) ? $n : '';
$page_mode = ($config->get('mode') !== null) ? $config->get('mode') : null;

$m = (isset($__pageAr[2])) ? $__pageAr[2] : null;

if (isset($id) && $id && $id != 'new') {
	$sid = ($config->get('sid') != null) ? $config->get('sid') : null;

	// set ID property of product
	$contest->id = $id;

	// read the details of product
	$contestView = $contest->readOne();
	extract($contestView);

	// Reset $id to ID property of product
	//$id = $contest->id;

$tp_start = strtotime($test_start);
$tp_now = strtotime('now');
$tp_end = $tp_start+(60*$test_time_min);
$test_end = date("Y-m-d H:i:s", $tp_end);
$test_now = date("Y-m-d H:i:s");
$d_start = new DateTime($test_start, new DateTimeZone('Etc/GMT-7'));
$d_end = new DateTime($test_end, new DateTimeZone('Etc/GMT-7'));
$d_now = new DateTime("now", new DateTimeZone('Etc/GMT-7'));
//print_r($d_start);
//print_r($d_end);
//print_r($d_now);
$timeOut = false;
//if ($d_now < $d_start) 
if ($d_end <= $d_now) $timeOut = true;

// get submissions of this id
$mySubmit->cid = $id;

if ($contest->uid) {
	if ($contest->team) {
		$team->id = $mySubmit->tid = $contest->uid;
		$contest->teamInfo = $team->sReadOne();
		$mySubmit->team = true;
	} else {
		$team->id = $mySubmit->tid = 0;
		$mySubmit->team = false;
	}
}

	// other pages
	if ($m == 'discussions') {
        $did = (isset($__pageAr[3])) ? $__pageAr[3] : null;
        if ($did) {
            $contest->did = $did;
            $dc = $contest->getOneDiscussion();
            $did = $dc['id'];
        } 
        if (!$did) $dcList = $contest->getDiscussions();
	} else if ($m) {
		$problem->id = $m;
		$pIn = $problem->readOne(true);

		// something with submissions
		$mySubmit->iid = $problem->id;

		if ($timeOut == false && $contest->uid) {
			$mySubmit->filesAr = $listFiles = $mySubmit->listFiles();

		//	$topSubmissions = $problem->topSubmissions();
		//	$subsList = $problem->getSubmissions();

			if (count($listFiles) <= 0) {
				$mySubmit->_fLang = $problem->lang[0];
				$mySubmit->_fNum = 0;
				$mySubmit->newFile();
			}
		} else {
			// change stt of these problem to 2
			if ($problem->stt == 1) $problem->changeStt(2);
			$mySubsList = $mySubmit->readAllMy();
		}
	} else {
		$teamsList = $team->readAllMy();

		// get score of each problem and calculate test score
		$totalScore = 0;
		$hasSubmit = false;
		foreach ($problems as $pK => $pO) {
			$mySubmit->iid = $pO['id'];
			$mySubmit->cid = $contest->id;
			$myMaxSub = $mySubmit->getMaxScoreSubmission();
			if ($myMaxSub) {
				$totalScore += $myMaxSub['score'];
				$pO['AC'] = (isset($myMaxSub['AC'])) ? $myMaxSub['AC'] : 0;
				$pO['tests'] = $myMaxSub['tests'];
				$pO['stt'] = ($myMaxSub['compile_stt'] == -1) ? 'error' : 'success';
				$pO['score'] = (isset($myMaxSub['score'])) ? $myMaxSub['score'] : 0;
				$pO['submitter'] = $myMaxSub['submitter'];
				$problems[$pK] = $pO;
				$hasSubmit = true;
			} else $pO['AC'] = $pO['tests'] = $pO['stt'] = $pO['score'] = 'null';
		}
		$totalMaxScore = count($problems)*100;
	}

}


if ($do) {
	if ($m == 'discussions') include 'system/'.$page.'/d_'.$do.'.php';
	else if ($m) include 'system/problems/'.$do.'.php';
	else include 'system/'.$page.'/'.$do.'.php';
} else {
	if ($page_mode == 'new') include 'views/'.$page.'/new.php';
	else if (!isset($id) || !$id) include 'views/'.$page.'/list.php';
	else include 'views/'.$page.'/view.php';
}
