<?php
class Compile extends Diff {
	
	public $console;
	
	public function _tokens ($input, $_fLang) {}		// function from abstract
	public function _tokensAr ($input, $tokens) {} 	// function from abstract
	
	public function compile ($all = false) {
		if (file_exists($this->_file)) {
			
			$_fLang = end(explode('.', $this->_file));

			$_file = $this->_file;
			$_testDir = $this->_dir.'/tests/';
			$_tests = $this->_tests = count(glob("{$_testDir}/*", GLOB_ONLYDIR));
			$meCode = $_fCode = end(explode('.', $_file)); // return cpp|java|...
			$myFileN = $_fileN = explode('.'.$_fCode, $_file)[0]; // Eg: ./data/code/p1/u1/cpp/1.(cpp)
			$_fnum = $_fn = $myFileName = end(explode('/', $_fileN)); // Eg: ./data/code/p1/u1/cpp/(1(.cpp))
			$_fileN = $_fileN.'.';
		//	$myFileDir = $this->_dir = explode($myFileName, $myFileN)[0];
			$myFileErrorTxt = $_fileErrorTxt = $_fileN.'error.txt';
			$_fSize = filesize($_file);
			$_dir = explode('/u', $_file)[0];
			
			$_dir = str_replace('./', MAIN_PATH_EXEC, $_dir);
			
			$_fu = explode('/', explode('/u', $_file)[1])[0];
			$_udir = $_dir.'/u'.$_fu;

			$u = preg_match('/u(.*)\//', $_file);

            $ext = (__HOST == 'ubuntu') ? 'out' : 'exe';
            
			if ($_fLang == 'cpp') {
				$CC = (__HOST == 'ubuntu') ? 'g++ -std=c++14' : 'g++';
				$myFileOutExe = $_fileOutExe = $_fileN.$ext; // execution file
			} else if ($_fLang == 'c') {
				$CC = 'gcc';
				$myFileOutExe = $_fileOutExe = $_fileN.$ext; // execution file
			} else if ($_fLang == 'java') {
				$CC = 'javac';
				$myFileOutExe = $_fileOutExe = $_fileN.'class'; // execution file
			} else if ($_fLang == 'py') {
				$CC = 'python3.2';
				$myFileOutExe = $_fileOutExe = $_fileN.'py'; // execution file
			}

			if (__HOST == 'ubuntu') $cmd = $CC.' '.$_file.' -o '.$_fileOutExe.' -lncurses -Wfatal-errors 2>'.$_fileErrorTxt;
			else {
				$_fileContent = file_get_contents($_file);
				file_put_contents($_file, str_replace('getch();', '', $_fileContent));
				$cmd = $CC.' '.$_file.' -O3 -o '.$_fileOutExe.' -Wfatal-errors 2>'.$_fileErrorTxt;
			}
			
	//		echo $cmd.' ~~~';
			exec($cmd);
			$error = file_get_contents($_fileErrorTxt);

            
	//		exec("chmod -R 777 ".$_fileOutTxt); 
	//		if (__HOST == 'ubuntu') 
				exec("chmod -R 777 ".$_fileOutExe); 

			$corrects = 0;
			$allOutput = $check = $allInput = $allCOutput = array();

			if ($_fLang == 'cpp') $_fTime = true;
			else $_fTime = false;
			
			if (!strlen($error)) {
				
				// Calculate time execution
				if ($_fTime == true) { // cpp supported
					$_fileTime = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$_fCode;
					$_fileTimeOut = $_dir.'/u'.$_fu.'.'.$_fn.'.time.'.$ext;
	//				echo $_fileTimeOut.'~~~~';
	//				$_fileStr = file_get_contents($_file);
	//				$_codeFContent = $_fileStr = '#include <time.h>'.$this->_codeFContent;
					$_fileStr = "#include<time.h>\n".file_get_contents($_file);
					preg_replace('/int main() {|int main () {|int main(){/', 'int main() {', $_fileStr);
					$_fileStr = str_insert_after($_fileStr, 'int main() {', "\nclock_t tStart = clock();\n");
					$_fileStr = str_insert_before($_fileStr, 'return 0;', "\nprintf(\"[mtime]%.2fs[/mtime]\", (double)(clock() - tStart)/CLOCKS_PER_SEC);\n");
					file_put_contents($_fileTime, $_fileStr);
					
					if (__HOST == 'ubuntu') $cmdTime = $CC.' '.$_fileTime.' -o '.$_fileTimeOut.' -lncurses -Wfatal-errors';
					else $cmdTime = $CC.' '.$_fileTime.' -O3 -o '.$_fileTimeOut;

					exec($cmdTime);
	//				echo $cmdTime.'~~~';
//					echo $_fileTimeOut.'~~~'.$cmdTime.'|';
				}

				$testAr = array();
				if ($_tests && $_tests > 0 && $_testDir) {
					for ($i = 1; $i <= $_tests; $i++) {
						$_fileInTxt = $_testDir.$i.'/test.in.txt';
						$_fileOutTxt = $_testDir.$i.'/test.out.u'.$u.'.txt';
						$_fileSampleOutTxt = $_testDir.$i.'/test.out.txt';

						if ($_fTime == true) {
							$cmdRun = $_fileTimeOut.' < '.$_fileInTxt;
//							echo $cmdRun.'~';
						
							$out = exec($cmdRun);
							preg_match('/\[mtime\](.*)\[\/mtime\]/', $out, $eTimeAr);
							$_eTime = $eTimeAr[1];
							$output = explode('[mtime]', $out)[0];
							$timeAr[$i] = $testAr[$i]['time'] = $_eTime;

						} else {
							$cmdRun = $_fileOutExe.' < '.$_fileInTxt;
							$output = exec($cmdRun);
						}
						
						$handle = fopen($_fileOutTxt, "w+");
						fwrite($handle, $output);
						fclose($handle);
						$allOutput[$i] = $testAr[$i]['output'] = $output;
						$allInput[$i] = $testAr[$i]['input'] = file_get_contents($_fileInTxt);

						$_sOutTxt = file_get_contents($_fileSampleOutTxt);
						$file = array_filter(array_map("trim", file($_fileSampleOutTxt)), "strlen");
						$_sOutTxt = implode("\n", $file);
						file_put_contents($_fileSampleOutTxt, $_sOutTxt);
						$allCOutput[$i] = $testAr[$i]['soutput'] = $_sOutTxt;

						// Compare output vs sample output
						$diff = Diff::compare($output, $_sOutTxt);
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
					if ($_fTime == true) {
						$cmdRun = $_fileTimeOut;
						$out = exec($cmdRun);
						preg_match('/\[mtime\](.*)\[\/mtime\]/', $out, $eTimeAr);
						$_eTime = $eTimeAr[1];
						$timeAr[$i] = $testAr[$i]['time'] = $_eTime;
						$output = explode('[mtime]', $out)[0];
					} else {
						$cmdRun = $_fileOutExe;
						$output = exec($cmdRun);
					}

                    $_fileOutTxt = $_testDir.'/test.out.u'.$u.'.txt';
					$handle = fopen($_fileOutTxt, "w+");
					fwrite($handle, $output);
					fclose($handle);
					exec("chmod -R 777 $_fileOutTxt");

						$_fileSampleOutTxt = $_testDir.'/test.out.txt';
						$_sOutTxt = file_get_contents($_fileSampleOutTxt);
						$file = array_filter(array_map("trim", file($_fileSampleOutTxt)), "strlen");
						$_sOutTxt = implode("\n", $file);
						file_put_contents($_fileSampleOutTxt, $_sOutTxt);

					// Compare output vs sample output
					$diff = Diff::compare($output, $_sOutTxt);
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
				}
			} else $_Ar = array('status' => 'error', 'stt' => -1, 'content' => $error);
				
		} else $_Ar = array('status' => 'error', 'stt' => -1, 'content' => 'No file found to compile.');

        $this->console = $_Ar;
        
	}

	function sCompile ($codeFile, $inputFile, $_fnum) {
		if (file_exists($codeFile) && file_exists($inputFile)) {
			
			$_file = $codeFile;
			$_fileInTxt = $inputFile;
			$_fLang = end(explode('.', $_file));
			
			$_fileN = explode('standard.'.$_fLang, $_file)[0].'test.'.$_fnum.'.'; // Eg: (./data/code/p1/u1/cpp/)(standard.(cpp))test.1

			$u = preg_match('/u(.*)\//', $_file);

            $ext = (__HOST == 'ubuntu') ? 'out' : 'exe';
            
			if ($_fLang == 'cpp') {
				$CC = (__HOST == 'ubuntu') ? 'g++ -std=c++14' : 'g++';
				$myFileOutExe = $_fileOutExe = $_fileN.$ext; // execution file
			} else if ($_fLang == 'c') {
				$CC = 'gcc';
				$myFileOutExe = $_fileOutExe = $_fileN.$ext; // execution file
			} else if ($_fLang == 'java') {
				$CC = 'javac';
				$myFileOutExe = $_fileOutExe = $_fileN.'class'; // execution file
			} else if ($_fLang == 'py') {
				$CC = 'python3.2';
				$myFileOutExe = $_fileOutExe = $_fileN.'py'; // execution file
			}

			$_fileErrorTxt = $_fileN.'error.txt';
			
//			$_fileContent = file_get_contents($_file);
//			file_put_contents($_file, str_replace('getch();', '', $_fileContent));
			if (__HOST == 'ubuntu') {
				$cmd = $CC.' '.$_file.' -o '.$_fileOutExe.' -lncurses -Wfatal-errors';
			} else {
				$cmd = $CC.' '.$_file.' -O3 -o '.$_fileOutExe.' -Wfatal-errors';
			}
			
			$cmdExe = exec($cmd);
			$error = file_get_contents($_fileErrorTxt);
			
			exec("chmod -R 777 ".$_fileOutExe); 
			
			$cmdRun = '"'.$_fileOutExe.'" < "'.$_fileInTxt.'"';
			$output = exec($cmdRun); 
						
			return $output;
		}
		return false;
	}
	
}
