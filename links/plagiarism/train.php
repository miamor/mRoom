<?
session_start();
error_reporting(E_ERROR | E_PARSE);
include 'func.php';
//session_destroy();

$docAr = array(
	1 => array(
		'fileName' 	=> 'data/1.format.cpp',
		'Author' 	=> 1,
	),
	2 => array(
		'fileName' 	=> 'data/2.format.cpp',
		'Author' 	=> 1,
	),
	3 => array(
		'fileName' 	=> 'data/3.format.cpp',
		'Author' 	=> 1,
	),
	5 => array(
		'fileName' 	=> 'data/5.format.cpp',
		'Author' 	=> 2,
	),
	6 => array(
		'fileName' 	=> 'data/6.format.cpp',
		'Author' 	=> 2,
	),
);

// Save details of each document to SESSION
foreach ($docAr as $dk => $doc) {
	if (!isset($_SESSION['Author'][$dk]))
		$_SESSION['Author'][$dk] = $doc['Author'];
	if (!isset($_SESSION['LWC'][$dk]))
		$_SESSION['LWC'][$dk] = getLWC(file_get_contents($doc['fileName']));
	if (!isset($_SESSION['LTF'][$dk]))
		$_SESSION['LTF'][$dk] = getLTF(file_get_contents($doc['fileName']));
}

// Get details of each document from SESSION
$Author_Ar = $_SESSION['Author'];
$LWC_Ar = $_SESSION['LWC'];
$LTF_Ar = $_SESSION['LTF'];
echo '<code>';print_r($Author_Ar);echo '</code>';
echo '<hr/><code>';print_r($LWC_Ar);echo '</code>';
echo '<hr/><code>';print_r($LTF_Ar);echo '</code>';


/*
// LWC
echo '<table width="100%" valign="top">
<thead>';
foreach ($docAr as $dk => $doc) echo '<th>Doc '.$dk.'</th>';
echo '</thead>
<tbody>
<tr>';
foreach ($docAr as $dk => $doc) {
	$content = file_get_contents($doc);

	// re-indent code
	$content = preg_replace('!\s+!', ' ', $content);
	$content = preg_replace('/(.*?) {/', '$1<br/>{<br/>', $content);
	$content = preg_replace('/(.*?);/', '$1;<br/>', $content);
	$content = preg_replace('/}/', '<br/>}<br/>', $content);
	$content = preg_replace("/(<br\s*\/?>\s*)+/", "<br/>", $content);
	$content = preg_replace('/\n/', '', $content);
	
	// tokenize whole documents (for displaying purpose)
	//$docTokenize[$dk] = _tokens(file_get_contents($doc));
	
	// get LWC
	$LWC[$dk] = getLWC($content);
	// print all LWC
	echo '<td>';
	echo 'Doc '.$dk.'<br/><code>';
	echo $content;
//	print_r($LWC[$dk]);
	echo '</code>';
	echo '<br/><br/><code>';print_r($LWC[$dk]['LWC']);echo '</code>';
	echo '</td>';
}
echo '</tr>
</tbody>
</table><hr/>';


// LTF
echo '<table width="100%" valign="top">
<thead>';
foreach ($docAr as $dk => $doc) echo '<th>Doc '.$dk.'</th>';
echo '</thead>
<tbody>
<tr>';
foreach ($docAr as $dk => $doc) {
	$content = file_get_contents($doc);

	// re-indent code
	$content = preg_replace('!\s+!', ' ', $content);
	$content = preg_replace('/(.*?) {/', '$1<br/>{<br/>', $content);
	$content = preg_replace('/(.*?);/', '$1;<br/>', $content);
	$content = preg_replace('/}/', '<br/>}<br/>', $content);
	$content = preg_replace("/(<br\s*\/?>\s*)+/", "<br/>", $content);
	$content = preg_replace('/\n/', '', $content);
	
	// tokenize whole documents (for displaying purpose)
	//$docTokenize[$dk] = _tokens(file_get_contents($doc));
	
	// get LTF
	$LTF[$dk] = getLTF($content);
	// print all LTF
	echo '<td>';
	echo 'Doc '.$dk.'<br/><code>';
	echo $content;
//	print_r($LTF[$dk]);
	echo '</code>';
	echo '<br/><br/><code>';print_r($LTF[$dk]['LTF']);echo '</code>';
	echo '</td>';
}
echo '</tr>
</tbody>
</table><hr/>';

// try compare 4 to 1
// using kNN to calculate distance between these 2 documents
// compare with LWC
$m = 0;
foreach ($LWC[4]['LWC'] as $tk => $oneT) {
	if (!isset($LWC[1]['LWC'][$tk])) $vp = 0;
	else $vp = $LWC[1]['LWC'][$tk];
//	echo $tk.' => '.$oneT.'~~~~~~'.$vp.'~~~~~~~~~<br/>';
	$m += ($oneT - $vp)*($oneT - $vp);
}
$d14['LWC'] = round(sqrt($m), 2);
// compare with LTF
$l = 0;
foreach ($LTF[4]['LTF'] as $tk => $oneT) {
	if (!isset($LTF[1]['LTF'][$tk])) $vp = 0;
	else $vp = $LTF[1]['LTF'][$tk];
//	echo $tk.' => '.$oneT.'~~~~~~'.$vp.'~~~~~~~~~<br/>';
	$l += ($oneT - $vp)*($oneT - $vp);
}
$d14['LTF'] = round(sqrt($l), 2);
//print_r($docTokenize);
echo '<h3>Distance between 4 to 1 is: ';
print_r($d14);
; echo '</h3>
<h4>Details</h4>
<table width="100%">
<thead>
<th>Doc4</th>
<th>Doc1</th>
</thead>
<tbody>
<tr>
<td>'.$LWC[4]['txt'].'</td>
<td>'.$LWC[1]['txt'].'</td>
</tr>
</tbody>
</table>';