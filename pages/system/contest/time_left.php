<?php 
$tp_start = strtotime($test_start);
$tp_now = strtotime('now');

$tp_end = $tp_start+(60*$test_time_min);
$test_end = date("Y-m-d H:i:s", $tp_end);
//$test_now = date("Y-m-d H:i:s");

$d_start = new DateTime($test_start);

//echo $test_start.'~~~~'.$test_end.'~~~~~'.$test_now.'~~~~~~~~~~~';

$d_start = new DateTime($test_start, new DateTimeZone('Etc/GMT-7'));
$d_end = new DateTime($test_end, new DateTimeZone('Etc/GMT-7'));
$d_now = new DateTime("now", new DateTimeZone('Etc/GMT-7'));
//echo $test_end.' ~~ '.$test_now.' ======= ';
//print_r($d_start);
//print_r($d_now);
if ($d_now < $d_start) { // test not start
	$diff = $d_now->diff($d_start);
//	print_r($diff);
	if ($diff->y > 0) {
		echo $diff->y.'y to start';
	} else if ($diff->m > 0) {
		echo $diff->m.'m to start';
	} else if ($diff->d > 0) {
		echo $diff->d.'d to start';
	} else if ($diff->h > 0) {
		echo $diff->h.'h to start';
	} else if ($diff->i > 0) {
		echo $diff->i.' min to start';
	} else if ($diff->s > 0) {
		echo $diff->s.' sec to start';
	} else {
		printf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
	}
}
else if ($d_end >= $d_now) {
	$diff = $d_end->diff($d_now);
//	print_r($diff);
	if ($diff->y > 0) {
		echo $diff->y.' year more';
	} else if ($diff->m > 0) {
		echo $diff->m.' month more';
	} else if ($diff->d > 0) {
		echo $diff->d.' day more';
	} else {
		printf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
	}
} else echo '00:00:00';
