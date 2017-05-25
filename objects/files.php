<?php
class Files extends Config {

	public function __construct() {
		parent::__construct();
	}

	function readFiles () {
		$dir = $this->fullDir = $this->fullDir;
		$_files = scandir($dir);
		foreach ($_files as $_k => $_fileO) {
			$_fileDir = $dir.'/'.$_fileO;
//			$_fOext = end(explode('.', $_fileO));
			if (in_array($_fileO, array('.', '..'))) unset($_files[$_k]);
			else {
				$fExt = end(explode('.', $_fileO));
				if (is_dir($_fileDir)) $fType = 'dir';
				else {
					if ($fExt == 'txt') $fType = 'text';
					else if (in_array($fExt, array('doc', 'docx'))) $fType = 'word';
					else if (in_array($fExt, array('pdf', 'pdfx'))) $fType = 'pdf';
					else if (in_array($fExt, array('ppt', 'pptx'))) $fType = 'powerpoint';
					else if (in_array($fExt, array('xls', 'xlsx'))) $fType = 'excel';
					else if (in_array($fExt, array('zip', 'tar', 'gz', '7z'))) $fType = 'zip';
					else if (in_array($fExt, array('mp3'))) $fType = 'audio';
					else if (in_array($fExt, array('mp4', 'wav'))) $fType = 'video';
					else if (in_array($fExt, array('png', 'jpg', 'jpeg', 'gif'))) $fType = 'image';
					else if (in_array($fExt, array('html', 'css', 'javascript', 'python'))) $fType = 'code';
					$fTypeTxt = '-'.$fType;
				}
				$_files[$_k] = array(
								'fN' => $_fileO, 
								'fExt' => $fExt,
								'fType' => $fType,
								'fTypeTxt' => $fTypeTxt
							);
			}
		}
		return array_values(array_filter($_files));
	}
	
	function readOne () {
		$dir = $this->fullDir;
		$_fileO = end(explode('/', $dir));
		if (is_file($dir)) {
		}
			$fExt = end(explode('.', $_fileO));
			if (is_dir($_fileDir)) $fType = 'dir';
			else {
				if ($fExt == 'txt') $fType = 'text';
				else if (in_array($fExt, array('doc', 'docx'))) $fType = 'word';
				else if (in_array($fExt, array('pdf', 'pdfx'))) $fType = 'pdf';
				else if (in_array($fExt, array('ppt', 'pptx'))) $fType = 'powerpoint';
				else if (in_array($fExt, array('xls', 'xlsx'))) $fType = 'excel';
				else if (in_array($fExt, array('zip', 'tar', 'gz', '7z'))) $fType = 'zip';
				else if (in_array($fExt, array('mp3'))) $fType = 'audio';
				else if (in_array($fExt, array('mp4', 'wav'))) $fType = 'video';
				else if (in_array($fExt, array('png', 'jpg', 'jpeg', 'gif'))) $fType = 'image';
				else if (in_array($fExt, array('html', 'css', 'javascript', 'python'))) $fType = 'code';
				$fTypeTxt = '-'.$fType;
			}
			$_files = array(
						'fN' => $_fileO, 
						'fName' => urldecode($_fileO),
						'fFullDir' => $dir,
						'fExt' => $fExt,
						'fType' => $fType,
						'fTypeTxt' => $fTypeTxt
					);
		return $_files;
	}
	
}
