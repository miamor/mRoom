<?php
class sCompile {
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
			
			//echo '<br/>'.$cmd.'<br/>'.$cmdExe.'<br/>'.$cmdRun.'<br/>'.$output;
			
			return $output;
		}
		return false;
	}
}