<?php
class sCompileClass {
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
			
//			echo '<h3>'.$cmdRun.'</h3>';
//			echo '<b>'.$output.'</b><hr/>';
			return $output;
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
		
		if (!strlen($error)) return $cmdRun;
		else return false;
	}

}