<?php
class Web extends Config {

	// database connection and table name
//	private $conn;
	private $table_name = "submissions";

	public function __construct() {
		parent::__construct();
	}

	public function newFile ($_name, $_type, $u = null) {
		if (!$u) $u = $this->u;
		$this->_dir = $_dir = $this->wDir;
		$this->_udir = $_udir = $_fdir = $this->_dir.'/u'.$u;
		
		if (!is_dir($_fdir)) mkdir($_fdir, 0777);
		exec('chmod -R 777 '.$_fdir);

		if ($_type == 'file') {
			$_file = $_fdir.'/'.$_name;
			$handle = fopen($_file, 'w') or die('Cannot open file:  '.$_file); 
			fwrite($handle, '');
			return true;
		} else {
			$_ndir = $_fdir.'/'.$_name;
			$n = 1;
			while (!is_dir($_ndir)) {
				$_ndir = $_ndir.' ('.$n.')';
			}
			mkdir($_ndir, 0777);
			exec('chmod -R 777 '.$_ndir);
			return true;
		}
		return false;
	}

	public function listFiles ($u = null, $uType = true, $dir) {
		if (!$u) $u = $this->u;
		$dDir = $this->wDir;
		if (!$dir) $dir = $this->dir = $dDir.'/u'.$u;
		$_files = scandir($dir);
		foreach ($_files as $_k => $_fileO) {
			$_fileDir = $dir.'/'.$_fileO;
			if (in_array($_fileO, array('.', '..'))) unset($_files[$_k]);
			else {
				if (is_dir($_fileDir)) {
					echo 'isdir: '.$_k.'~'.$_fileDir;
					$_files[$_k] = array(
						'name' => $_fileO,
						'files' => $this->listFiles('', '', $_fileDir)
					);
				}
			}
		}
		return array_values(array_filter($_files));
	}

	public function showFiles ($u = null, $uType = true, $dir = null) {
		if (!$u) $u = $this->u;
		$dDir = $this->wDir;
		if (!$dir) $dir = $this->dir = $dDir.'/u'.$u;
		$_files = scandir($dir);
		foreach ($_files as $_k => $_fileO) {
			$_fileDir = $dir.'/'.$_fileO;
			if (in_array($_fileO, array('.', '..'))) unset($_files[$_k]);
			else {
				$isDir = is_dir($_fileDir);
				if (strpos($_fileO, '.')) $ext = end(explode('.', $_fileO));
				else $ext = 'html';
				if ($_fileO == 'README') $cls = 'active';
				else $cls = '';
				echo '<li>';
				echo '<a class="list-group-item media me-file '.$cls.'" data-type="'.$ext.'" data-dir="'.$_fileDir.'">'.$_fileO.'</a>';
				if ($isDir) {
					echo '<ul>';
					$this->showFiles('', '', $_fileDir);
					echo '</ul>';
				}
				echo '</li>';
			}
		}
	}
	
	function getCodeContent ($file = null) {
//		$this->ext = $this->mode = end(explode('.', $this->file));
//		if ($this->ext == 'cpp') $this->mode = 'c_cpp';
		if (is_file($this->file)) $this->codeContent = file_get_contents($this->file);
		return $this->codeContent;
	}

}
?>
