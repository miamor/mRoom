<?php
class Compile extends Diff {
	
	public $console;
	
	public function _tokens ($input, $_fLang) {}		// function from abstract
	public function _tokensAr ($input, $tokens) {} 	// function from abstract
	
	function formatOutput ($string = null) {
		if ($string) {
			return preg_replace("/(\t|\s|\n|\r|\r\n|\n\r|\r\r|\n\n)+/", " ", trim($string));
		}
	}
	
	public function compile ($all = false) {
		$_file = $this->_file;
		if (file_exists($_file)) {
			$this->_fLang 	= $this->meCode 	= $meCode = $_fLang = end(explode('.', $_file));
			$this->_testDir = $_testDir = $this->_dir.'/tests/';
			$this->_tests 	= $_tests = count(glob("{$_testDir}/*", GLOB_ONLYDIR));
		//	$myFileDir = $this->_dir = explode($myFileName, $myFileN)[0];
			$this->$_fSize 	= $_fSize = filesize($_file);
			$_dir = explode('/u', $_file)[0];

			$_dir = str_replace('./', MAIN_PATH_EXEC, $_dir);
			$this->__dir 	= $_dir;
			
			$this->filenameWithDir = $this->myFileN = $myFileN = explode('.'.$_fLang, $_file)[0]; // Eg: ./data/code/p1/u1/java/java_1

			$this->filename = $filename = $this->_fn = $_fn = end(explode('/', $myFileN)); // Eg: (./data/code/p1/u1/java/)java_1(.java)
			$this->filedir = $filedir = explode($filename.'.'.$_fLang, $_file)[0]; // Eg: ./data/code/p1/u1/java/
//			echo '[_file]'.$_file.'[/_file][filename]'.$filename.'[/filename][filedir]'.$filedir.'[/filedir]~~~~~~~';

			$this->_fileN = $_fileN = $myFileN.'.'; // Eg: ./data/code/p1/u1/java/java_1.
			$this->_fileErrorTxt = $myFileErrorTxt = $_fileErrorTxt = $_fileN.'error.txt';
			
			$this->_fu 		= $_fu = explode('/', explode('/u', $_file)[1])[0];
			$this->_udir 	= $_udir = $_dir.'/u'.$_fu;

			$u = preg_match('/u(.*)\//', $_file);
			
			if ($_fLang == 'cpp') $cmdRun = $this->compileCpp();
			else if ($_fLang == 'c') $cmdRun = $this->compileC();
			else if ($_fLang == 'java') $cmdRun = $this->compileJava();
			else if ($_fLang == 'python') $cmdRun = $this->compilePython();

			if ($_fLang == 'cpp' || $_fLang == 'c') $_fTime = true;
			else $_fTime = false;
			
		//	echo $cmdRun;
		//	exec($cmdRun);
	
			if ($cmdRun) {
				// got command to execute file, now get it to work
				$testAr = array();
				if ($_tests && $_tests > 0 && $_testDir) {
					for ($i = 1; $i <= $_tests; $i++) {
						$_fileInTxt = $_testDir.$i.'/test.in.txt';
						$_fileOutTxt = $_testDir.$i.'/test.out.u'.$u.'.txt';
						$_fileSampleOutTxt = $_testDir.$i.'/test.out.txt';

						$cmd_Run = $cmdRun.' < '.$_fileInTxt.' > '.$_fileOutTxt;
						$exec_out = exec($cmd_Run);
						$out = file_get_contents($_fileOutTxt);
					//	echo $cmd_Run.' ~ '.$exec_out.' ~ '.$out.' ------------- ';
						
						if ($_fTime == true) {
							preg_match('/\[mtime\](.*)\[\/mtime\]/', $out, $eTimeAr);
							$_eTime = $eTimeAr[1];
							$output = explode('[mtime]', $out)[0];
							$timeAr[$i] = $testAr[$i]['time'] = $_eTime;
						} else $output = $out;
					//	echo $out.'~~~';
						// edit my output
						$myOutTxtAr = array_values(array_filter(explode("\n", rtrim($output))));
						$_myOutTxt = $this->formatOutput(implode("\n", $myOutTxtAr));
					/*	$_myOutTxt = array_filter(array_map("trim", file($_fileOutTxt)), "strlen");
						$_myOutTxt = $_myOutTxt[0];
					*/	file_put_contents($_fileOutTxt, $_myOutTxt);

						exec("chmod 777 $_fileOutTxt");

						$allOutput[$i] = $testAr[$i]['output'] = $_myOutTxt;
						$allInput[$i] = $testAr[$i]['input'] = file_get_contents($_fileInTxt);
						
						//echo $cmd_Run.' ======== ';
						//print_r($allOutput);
						// edit sample output
						// this was edited when submitting new problem
						$_sOutTxt = $this->formatOutput(file_get_contents($_fileSampleOutTxt));
					/*	$sOutTxt = rtrim(file_get_contents($_fileSampleOutTxt);
						$sOutTxtAr = array_values(array_filter(explode("\n", $sOutTxt)));
						$_sOutTxt = rtrim(implode("\n", $sOutTxtAr));
						$_sOutTxt = rtrim($sOutTxt);
						file_put_contents($_fileSampleOutTxt, $_sOutTxt);
						$_sOutTxt = rtrim(file_get_contents($_fileSampleOutTxt));
						$_sOutTxt = array_filter(array_map("trim", file($_fileSampleOutTxt)), "strlen");
						print_r($_sOutTxt);
						$_sOutTxt = $this->formatOutput($_sOutTxt[0]);
					*/	$allCOutput[$i] = $testAr[$i]['soutput'] = $_sOutTxt;

						// Compare output vs sample output
						$diff = Diff::compare($_myOutTxt, $_sOutTxt);
						$check[$i] = false;

						foreach ($diff as $dif) {
							if ($dif[1] == 0) {
								$check[$i] = true;
								$corrects++;
							} else {
								$check[$i] = false;
								break;
							}
						}
						$checkTxt[$i] = ($check[$i]) ? 'AC' : 'WA';
						$testAr[$i]['check'] = $check[$i];
						$testAr[$i]['checkTxt'] = $checkTxt[$i];
					}
					$_Ar = array('status' => 'success', 'stt' => 0, 'check' => $check[1], 'checkTxt' => $checkTxt[1], 'input' => $allInput[1], 'output' => $allOutput[1], 'correct_output' => $allCOutput[1], 'time' => $timeAr[1]);
					if ($all == true) $_Ar['tests'] = $testAr;
				} else {
					$_fileOutTxt = $_testDir.'/test.out.u'.$u.'.txt';
					$cmdRun = $cmdRun.' > '.$_fileOutTxt;
					//$out = exec($cmdRun);
					exec($cmdRun);
					$out = file_get_contents($_fileOutTxt);
					
					if ($_fTime == true) {
						preg_match('/\[mtime\](.*)\[\/mtime\]/', $out, $eTimeAr);
						$_eTime = $eTimeAr[1];
						$timeAr[$i] = $testAr[$i]['time'] = $_eTime;
						$output = explode('[mtime]', $out)[0];
					} else $output = $out;
					
				/*	$_fileOutTxt = $_testDir.'/test.out.u'.$u.'.txt';
					$handle = fopen($_fileOutTxt, "w+");
					fwrite($handle, $output);
					fclose($handle);
				*/
					
					// edit my output
				/*	$_myOutTxt = array_filter(array_map("trim", file($_fileOutTxt)), "strlen");
					$_myOutTxt = $_myOutTxt[0];
				*/	$myOutTxtAr = array_values(array_filter(explode("\n", rtrim($output))));
					$_myOutTxt = $this->formatOutput(implode("\n", $myOutTxtAr));
					file_put_contents($_fileOutTxt, $_myOutTxt);

					exec("chmod 777 $_fileOutTxt");

				/*	no tests => no compare codes
					// edit sample output
					$_fileSampleOutTxt = $_testDir.'/test.out.txt';
					$_sOutTxt = array_filter(array_map("trim", file($_fileSampleOutTxt)), "strlen");
					$_sOutTxt = $this->formatOutput($_sOutTxt[0]);
					file_put_contents($_fileSampleOutTxt, $_sOutTxt);

					// Compare output vs sample output
					$diff = Diff::compare($_myOutTxt, $_sOutTxt);
					$check = false;

					foreach ($diff as $dif) {
						if ($dif[1] == 0) {
							$check = true;
							$corrects++;
						} else {
							$check = false;
							break;
						}
					}
					$checkTxt = ($check) ? 'AC' : 'WA';

					$_Ar = array('status' => 'success', 'stt' => 0, 'output' => $output, 'correct_output' => $_sOutTxt, 'check' => $check, 'checkTxt' => $checkTxt);
				*/
					$_Ar = array('status' => 'success', 'stt' => 0, 'output' => $output);
				}
			} else {
				$_Ar = array('status' => 'error', 'stt' => -1, 'content' => 'Errors when generating executable file. <br/>'.$this->errorGenExec);
			}
			
			$error = file_get_contents($_fileErrorTxt);

		} else $_Ar = array('status' => 'error', 'stt' => -1, 'content' => 'No file found to compile.');

		$this->console = $_Ar;
	}

	function error ($str, $file) {
		// replace path to ... to avoid spoiling
//		$find = MAIN_PATH_EXEC.substr($file, 2);
		$str = str_replace(' ', '&nbsp;', $str);
		$string = str_replace($file, '<i>[codefile]</i>', nl2br($str));
		return $string;
	}
	
	function compileCpp () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;

		$ext = (__HOST == 'ubuntu') ? 'out' : 'exe';

		$_testDir = $this->_testDir;
		$_tests = $this->_tests;

		$myFileN = $this->myFileN; // Eg: ./data/code/p1/u1/cpp/1.(cpp)
		$_fn = $this->_fn; // Eg: ./data/code/p1/u1/cpp/(1(.cpp))
		$_fSize = $this->_fSize;
		$_dir = $this->__dir;
			
		$_fu = $this->_fu;
		$_udir = $this->_udir;

		$_fileContent = file_get_contents($_file);
//		file_put_contents($_file, str_replace('getch();', '', $_fileContent));

		$_fileTime = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$_fLang;
		$_fileTimeOut = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$ext;
		$_fileStr = "#include <time.h>\n".rtrim($_fileContent);
//		$_fileStr = preg_replace('/int main\(\) \{|int main \(\) \{|int main\(\)\{/', 'int main() {', $_fileStr);
		$_fileStr = preg_replace("/int((\r\n|\r|\n|\\s|\t)+)main\(\)/", 'int main() ', $_fileStr);
		$_fileStr = preg_replace("/int((\r\n|\r|\n|\\s|\t)+)main((\r\n|\r|\n|\\s|\t)+)\(\)/", 'int main() ', $_fileStr);
		$_fileStr = preg_replace("/int main\(\)((\r\n|\r|\n|\\s|\t)+){/", 'int main() {', $_fileStr);
		$_fileStr = preg_replace("/int main((\r\n|\r|\n|\\s|\t)+)\(\)((\r\n|\r|\n|\\s|\t)+){/", 'int main() {', $_fileStr);
		if (!preg_match("include((\r\n|\r|\n|\\s|\t)+)\<stdio", $_fileStr)) $_fileStr = "#include<stdio.h>\n".$_fileStr;
		$_fileStr = str_replace(array('#include<curses.h>', 'getch();'), array('', ''), $_fileStr);
		$_fileStr = str_insert_after($_fileStr, 'int main() {', "\nclock_t tStart = clock();\n");
//		$_fileStr = str_insert_before($_fileStr, 'return 0;', "\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n");
		$patt = "/((int main\(\) \{)([^#]+))(return 0)([^#]+)/";
		if (preg_match($patt, $_fileStr)) {
			$_fileStr = preg_replace($patt, "$1\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n$4$5", $_fileStr);
		}
		else {
			$_fileStr = substr(rtrim($_fileStr), 0, -1);
			$_fileStr .= "\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n}";
		}
		file_put_contents($_fileTime, $_fileStr);
	//	echo $_fileStr.' ============== ';
		
//		if (!file_exists($_fileTimeOut)) {
			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'g++ -std=c++14 '.$_fileTime.' -o '.$_fileTimeOut.' -lncurses -Wfatal-errors 2>'.$_fileErrorTxt;
//			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'g++ -std=c++14 '.$_fileTime.' -o '.$_fileTimeOut.' -Wfatal-errors';
			else $cmd = EXEC_PATH_C_CPP.'g++ '.$_fileTime.' -O3 -o '.$_fileTimeOut.' 2>'.$_fileErrorTxt;

			$out = exec($cmd); // done getting time and checking errors
			
			$error = $this->error(file_get_contents($_fileErrorTxt), $_fileTime);
//		}
		$this->errorGenExec = $error;
		if (!strlen($error)) return $_fileTimeOut;
		else return false;
	}
	
	function compileC () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;

		$_testDir = $this->_testDir;
		$_tests = $this->_tests;

		$myFileN = $this->myFileN; // Eg: ./data/code/p1/u1/cpp/1.(cpp)
		$_fn = $this->_fn; // Eg: ./data/code/p1/u1/cpp/(1(.cpp))
		$_fSize = $this->_fSize;
		$_dir = $this->__dir;
			
		$_fu = $this->_fu;
		$_udir = $this->_udir;

		$ext = (__HOST == 'ubuntu') ? 'out' : 'exe';

		$_fileContent = file_get_contents($_file);
//		file_put_contents($_file, str_replace('getch();', '', $_fileContent));

		$_fileTime = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$_fLang;
		$_fileTimeOut = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$ext;
		$_fileStr = "#include <time.h>\n".rtrim($_fileContent);
/*		$_fileStr = preg_replace('/int main\(\) \{|int main \(\) \{|int main\(\)\{/', 'int main() {', $_fileStr);
		$_fileStr = preg_replace("/int main((\r\n|\r|\n|\\s|\t)+)\(\)((\r\n|\r|\n|\\s|\t)+){/", 'int main() {', $_fileStr);
		if (!preg_match("include\<stdio", $_fileStr)) $_fileStr = "#include<stdio.h>\n".$_fileStr;
*/
		$_fileStr = preg_replace("/int((\r\n|\r|\n|\\s|\t)+)main\(\)/", 'int main() ', $_fileStr);
		$_fileStr = preg_replace("/int((\r\n|\r|\n|\\s|\t)+)main((\r\n|\r|\n|\\s|\t)+)\(\)/", 'int main() ', $_fileStr);
		$_fileStr = preg_replace("/int main\(\)((\r\n|\r|\n|\\s|\t)+){/", 'int main() {', $_fileStr);
		$_fileStr = preg_replace("/int main((\r\n|\r|\n|\\s|\t)+)\(\)((\r\n|\r|\n|\\s|\t)+){/", 'int main() {', $_fileStr);
		if (!preg_match("include((\r\n|\r|\n|\\s|\t)+)\<stdio", $_fileStr)) $_fileStr = "#include<stdio.h>\n".$_fileStr;
		$_fileStr = str_replace(array('#include<curses.h>', 'getch();'), array('', ''), $_fileStr);
		$_fileStr = str_insert_after($_fileStr, 'int main() {', "\nclock_t tStart = clock();\n");
//		$_fileStr = str_insert_before($_fileStr, 'return 0;', "\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n");
		$patt = "/((int main\(\) \{)([^#]+))(return 0)([^#]+)/";
		if (preg_match($patt, $_fileStr)) {
			$_fileStr = preg_replace($patt, "$1\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n$4$5", $_fileStr);
		}
		else {
			$_fileStr = substr(rtrim($_fileStr), 0, -1);
			$_fileStr .= "\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n}";
		}
		file_put_contents($_fileTime, $_fileStr);

//			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'gcc '.$_file.' -o '.$_fileTimeOut.' -lncurses -Wfatal-errors 2>'.$_fileErrorTxt;
			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'gcc '.$_fileTime.' -o '.$_fileTimeOut.' 2>'.$_fileErrorTxt;
			else $cmd = EXEC_PATH_C_CPP.'gcc '.$_fileTime.' -O3 -o '.$_fileTimeOut.' 2>'.$_fileErrorTxt;

			$out = exec($cmd); // done getting time and checking errors
			
			$error = $this->error(file_get_contents($_fileErrorTxt), $_fileTime);
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return $_fileTimeOut;
		else return false;
	}	
	
	function compileJava () {
		$_file = $this->_file;
		
		$_fLang = $this->_fLang;
		$_testDir = $this->_testDir;
		$_tests = $this->_tests;

		$myFileN = $this->myFileN; // Eg: ./data/code/p1/u1/cpp/1.(cpp)
		$_fn = $this->_fn; // Eg: ./data/code/p1/u1/cpp/(1(.cpp))
		$_fileN = $this->_fileN;

		$_fileErrorTxt = $this->_fileErrorTxt;
		$_fSize = $this->_fSize;
		$_dir = $this->__dir;
			
		$_fu = $this->_fu;
		$_udir = $this->_udir;

		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;
		$codeFileName = $this->filename;
		$filedir = $this->filedir;

//		$_fileOutExec = $codeFileName.'.class';
		$_fileOutExec = $filedir.$codeFileName;

		$cmd = EXEC_PATH_JAVA.'javac '.$_file.' 2>'.$_fileErrorTxt;
		exec($cmd); // done getting time and checking errors
//		$error = file_get_contents($_fileErrorTxt);
		$error = $this->error(file_get_contents($_fileErrorTxt), $_file);
		$cmdRun = EXEC_PATH_JAVA.'java -cp '.$filedir.' '.$codeFileName;
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return $cmdRun;
		else return false;
	}
	
	// CHECK THIS OUT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
	function compilePython () {
		$_file = $this->_file;
		
		$_fLang = $this->_fLang;
		$_testDir = $this->_testDir;
		$_tests = $this->_tests;

		$myFileN = $this->myFileN; // Eg: ./data/code/p1/u1/cpp/1.(cpp)
		$_fn = $this->_fn; // Eg: ./data/code/p1/u1/cpp/(1(.cpp))
		$_fileN = $this->_fileN;

		$_fileErrorTxt = $this->_fileErrorTxt;
		$_fSize = $this->_fSize;
		$_dir = $this->__dir;
			
		$_fu = $this->_fu;
		$_udir = $this->_udir;

		$_fileContent = file_get_contents($_file);

		$cmd = 'python '.$_file.' 2>'.$_fileErrorTxt;
		exec($cmd); // done getting time and checking errors
		
		$error = file_get_contents($_fileErrorTxt);
		
		$cmdRun = 'python '.$_file.' 2>'.$_fileErrorTxt;
		if (!strlen($error)) return $cmdRun;
		else return false;
	}
	

	function sCompile ($codeFile, $inputFile, $_fnum) {
		if (file_exists($codeFile) && file_exists($inputFile)) {
			
			$this->_file = $_file = $codeFile;
			$_fileInTxt = $inputFile;
			$this->_fLang = $_fLang = end(explode('.', $_file));
			
			$this->filename = $filename = 'standard';
			$this->filenameWithDir = explode('.'.$_fLang, $_file)[0];  // Eg: (./data/code/p1/u1/cpp/standard)
			$this->filedir = $filedir = explode($filename.'.'.$_fLang, $_file)[0]; // Eg: (./data/code/p1/u1/cpp/)

			$filedir = explode('standard.'.$_fLang, $_file)[0]; // Eg: (./data/code/p1/u1/cpp/)
			$this->_fileN = $_fileN = $filedir.'test.'.$_fnum.'.'; // Eg: (./data/code/p1/u1/cpp/)(standard.(cpp))test.1

			//$this->_testDir = $filedir.'/tests';
			$this->_fileErrorTxt = $_fileErrorTxt = $_fileN.'error.txt';

			$u = preg_match('/u(.*)\//', $_file);
			
			//$ext = (__HOST == 'ubuntu') ? 'out' : 'exe';
            
			if ($_fLang == 'cpp') $cmd = $this->sCompileCpp();
			else if ($_fLang == 'c') $cmd = $this->sCompileC();
			else if ($_fLang == 'java') $cmd = $this->sCompileJava();
			else if ($_fLang == 'python') $cmd = $this->sCompilePython();
			
//			$cmdExe = exec($cmd);
//			$error = file_get_contents($_fileErrorTxt);
			
//			exec("chmod -R 777 ".$_fileOutExe); 
			
			$_fileOutTxt = str_replace('.in.txt', '.out.txt', $_fileInTxt);
			
//			$cmdRun = $cmd.' < '.$_fileInTxt;
//			$output = exec($cmdRun); 
			$cmdRun = $cmd.' < '.$_fileInTxt.' > '.$_fileOutTxt;
			exec($cmdRun);
			$output = file_get_contents($_fileOutTxt);
			$_sOutTxt = rtrim($output);
			file_put_contents($_fileOutTxt, $_sOutTxt);
			
//			echo '<h3>'.$cmdRun.'</h3>';
//			echo '<b>'.$output.'</b><hr/>';
			return $_sOutTxt;
		}
		return false;
	}
	
	function sCompileCpp () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;

        $ext = (__HOST == 'ubuntu') ? 'out' : 'exe';
		
		$_fileOutExec = $_fileN.$ext;
		
		$error = '';
		if (!file_exists($_fileOutExec)) {
			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'g++ -std=c++14 '.$_file.' -o '.$_fileOutExec.' -lncurses -Wfatal-errors 2>'.$_fileErrorTxt;
			else $cmd = EXEC_PATH_C_CPP.'g++ '.$_file.' -O3 -o '.$_fileOutExec.' 2>'.$_fileErrorTxt;

			exec($cmd); // done getting time and checking errors
			
			$error = file_get_contents($_fileErrorTxt);
		}
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return '"'.$_fileOutExec.'"';
		else return false;
	}
	
	function sCompileC () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;

        $ext = (__HOST == 'ubuntu') ? 'out' : 'exe';
		
		$_fileOutExec = $_fileN.$ext;
		
		$error = '';
		if (!file_exists($_fileOutExec)) {
			if (__HOST == 'ubuntu') $cmd = EXEC_PATH_C_CPP.'gcc '.$_file.' -o '.$_fileOutExec.' -lncurses -Wfatal-errors 2>'.$_fileErrorTxt;
			else $cmd = EXEC_PATH_C_CPP.'gcc '.$_file.' -O3 -o '.$_fileOutExec.' 2>'.$_fileErrorTxt;

			exec($cmd); // done getting time and checking errors
			
			$error = file_get_contents($_fileErrorTxt);
		}
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return '"'.$_fileOutExec.'"';
		else return false;
	}
	
	function sCompileJava () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;
		$codeFileName = $this->filename;
		$filedir = $this->filedir;

//		$_fileOutExec = $codeFileName.'.class';
		$_fileOutExec = $filedir.$codeFileName;

		$error = '';
		if (!file_exists($_fileOutExec.'.class')) {
			$cmd = EXEC_PATH_JAVA.'javac '.$_file.' 2>'.$_fileErrorTxt;

			exec($cmd); // done getting time and checking errors
			
			$error = file_get_contents($_fileErrorTxt);
		}
		$cmdRun = EXEC_PATH_JAVA.'java -cp '.$filedir.' '.$codeFileName;
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return $cmdRun;
		else return false;
	}
	
	function sCompilePython () {
		$_file = $this->_file;
		$_fLang = $this->_fLang;
		$_fileN = $this->_fileN;
		$_fileErrorTxt = $this->_fileErrorTxt;

		//$_fileOutExec = $_fileN.'class';
		
		$cmd = EXEC_PATH_PYTHON.'python '.$_file.' 2>'.$_fileErrorTxt;

		exec($cmd); // done getting time and checking errors
		
		$error = file_get_contents($_fileErrorTxt);
		
		$cmdRun = $cmd;
		
		$this->errorGenExec = $error;
		if (!strlen($error)) return $cmdRun;
		else return false;
	}

}
