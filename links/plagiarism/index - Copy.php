<? 
session_start();
error_reporting(E_ERROR | E_PARSE);
include_once '../../config/config.php';
//include '../../class/class.plagiarism.test.php';
include '../../class/class.plagiarism.php';
$config = new Config();
$pla = new Plagiarism();
$pla->_dir = 'M:\xampp\htdocs\mRoom\_pla\data';
$pla->train();
$trainedData = $pla->trainedData;

if ($_GET['do'] == 'compare') {
	$eg = false;
	$txtAr[1] = html_entity_decode($_POST['cont1']);
	$txtAr[2] = html_entity_decode($_POST['cont2']);
	$compareAr = array(
		array(1, 2)
	);
	echo '<h2>Result</h2>';
	showDetection($compareAr, $txtAr, false);
} else if ($_GET['do'] == 'find') {
	
}
else {
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="assets/dist/img/favicon.ico" />
	<title>Simple C++ Plagiarism Detection</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/samples.css">
	<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="page-head">
	<h1>Simple tests</h1>
</div>

<div class="intro">

<div class="howitworks">
<h2>How it works</h2>
<ol>
	<li>Beautify the code, remove libraries included.</li>
	<li>Format code.
		<ol class="note">
			<li>Rename similar tokens</li>
			<li>Rewrite printf-cout / scanf-cin,...</li>
		</ol>
		<div class="eg"><b>Eg</b>: 
			<ul>
				<li>(var) <b>token1</b> -> <b>a</b>, (var) <b>token2</b> -> <b>b</b>, (function) <b>func1</b> -> <b>a_15</b>, (function) <b>func2</b> -> <b>b_93</b>,...</li>
				<li><b>float a, b</b> -> <b>float a; float b</b></li>
				<li><b>printf / scanf</b> -> <b>cout / cin</b></li>
				<li><b>do {} while ()</b> -> <b>while {}</b></li>
			</ul>
		</div>
	</li>
	<li>Convert content to tokens.<br/>
		<b>Eg</b>: Given <code>int main () { return 0; }</code><br/>
		We will divide it into string of tokens, not characters.<br/>
		For example, The sequence of 4-grams derived from the content given will be something like <br/>
		<code>intmain()</code> <code>main(){</code> <code>(){return</code> <code>return0;}</code><br/>
		but not <code>intm</code> <code>main</code> <code>ain(</code>...
	</li>
	<li>Remove unnecessary tokens.<br/>
		<div class="eg"><b>Eg</b>: (brackets) {}...</div>
		<div class="note">Remove brackets so that <b>if {<i>[OneLineCode]</i>}</b> and <b>if <i>[OneLineCode]</i></b> will be catched.</div>
	</li>
	<li>Get fingerprints.<br/>
		By getting the minimum value of each group of k-value.
	</li>
</ol></div>

</div>

<div class="test">
	<h2>Test</h2>
	<form class="submit" method="post" action="?do=compare">
		<div class="col-lg-6 no-padding-left">
			<h4>Code content #1</h4>
			<textarea name="cont1" style="height:100px" class="form-control"></textarea>
		</div>
		<div class="col-lg-6 no-padding-right">
			<h4>Code content #2</h4>
			<textarea name="cont2" style="height:100px" class="form-control"></textarea>
		</div>
		<div class="clearfix"></div>
		<div class="btn-groups center" style="margin-top:10px">
			<input type="reset" class="btn btn-default" value="Reset"/>
			<input type="submit" class="btn btn-success" value="Submit"/>
		</div>
	</form>

	<div id="result-input"></div>
</div>

<div class="examples">
	<h2>Examples</h2>

	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#train" data-toggle="tab" aria-expanded="false">Train</a></li>
			<li><a href="#ml" data-toggle="tab" aria-expanded="true">Machine Learning</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="train">
		<?
		foreach ($trainedData as $k => $oneTrainedData) {
			$oD = $oneTrainedData['data'];
			echo '<div class="one-file">';
			echo '<h2>'.$oneTrainedData['file'].'</h2>';
			echo '<div class="col-lg-4 no-padding">
				<pre>'.$oD['txt'].'</pre>
			</div>
			<div class="col-lg-4">
				<pre>'; print_r($oD['tokens']); echo '</pre>
			</div>
			<div class="col-lg-4 no-padding">
				<pre>'; print_r($oD['tokensCountAr']); echo '</pre>
			</div>
			<div class="clearfix"></div>';
			echo '</div>';
		}
		?>
			</div><!-- /.tab-pane -->

			<div class="tab-pane" id="ml">
				<h3 class="ppd">Plagiarism detection using Machine Learning</h3>
				Code 1,2,3,5,6 are for training, <a href="?d=4" data-type="test_ml">4</a> and <a href="?d=7" data-type="test_ml">7</a> is test data.
			</div><!-- /.tab-pane -->
		</div><!-- /.tab-content -->
	</div>	

	<div class="result">
		<h2>Result</h2>
		<div id="result-eg"></div>
		<div class="clearfix"></div>
	</div>
</div>

<div class="page-foot">
	Footer
</div>

	<script src="js/beautify.js"></script>
	<script src="js/samples.js"></script>

</body>
</html>

<? }
