<?php
class Submission extends Config {

	// database connection and table name
//	private $conn;
	private $table_name = "submissions";
	public $id;
	public $uid;
	public $iid;
	public $sid;
	public $file;
	public $compile_stt;
	public $_dir;
	public $_udir;
	public $codeContent;

	public function __construct() {
		parent::__construct();
	}

	public function newFile ($u = null) {
		$this->_dir = $_dir = $this->codeDir.'/p'.$this->iid;
		if ($this->team) {
			if (!$u) $u = $this->tid;
			$this->_udir = $_udir = $this->_dir.'/ut'.$u;
		} else {
			if (!$u) $u = $this->u;
			$this->_udir = $_udir = $this->_dir.'/u'.$u;
		}

		$_fLang = $this->_fLang;
		$_fdir = $this->_udir.'/'.$this->_fLang;
		$_fNum = $this->_fNum;
		
		if (!is_dir($_udir)) mkdir($_udir, 0777);
		exec('chmod -R 777 '.$_udir);
		
		if (!is_dir($_fdir)) mkdir($_fdir, 0777);
		exec('chmod -R 777 '.$_fdir);

		if (!$_fNum) $_fNum = $this->getFilesNum($_fdir, $_fLang);

		$_fdir = $_udir.'/'.$_fLang;
		$_file = $_fdir.'/'.$_fLang.'_'.$_fNum.'.'.$_fLang;
		$_fileFormat = $_dir.'/u'.$u.'.'.$_fLang.'_'.$_fNum.'.'.$_fLang;

		$handle = fopen($_file, 'w') or die('Cannot open file:  '.$_file); 
		fwrite($handle, '// Your code goes here...');

		return $_fLang.'_'.$_fNum;
	}

	function readAllMy ($from_record_num = null, $records_per_page = null) {
		$lim = '';
		if ($from_record_num && $records_per_page) $lim = "LIMIT
					{$from_record_num}, {$records_per_page}";
		$cond = ($this->team) ? "tid = ?" : "uid = ?";
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE 
					iid = ? AND {$cond} AND team = ?
				ORDER BY
					modified DESC, created DESC
				{$lim}";
		$team = ($this->team) ? 1 : 0;

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->iid);
		
		if ($this->team) $stmt->bindParam(2, $this->tid);
		else $stmt->bindParam(2, $this->u);
		
		$stmt->bindParam(3, $team); // is team
		$stmt->execute();
		
		$subsList = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if (!$this->iid) {
				$row['pInfo'] = $this->sProbOne($row['iid']);
				$score += $row['score'];
			}
			$subsList[] = $row;
		}

		return $subsList;
	}
	function getMaxScoreSubmission () {
		$query = "SELECT
					uid,compile_stt,score,AC,compile_details
				FROM
					" . $this->table_name . "
				WHERE 
					iid = ? AND tid = ? AND submit = 1 AND cid = ? AND team = ?
				ORDER BY
					score DESC, modified DESC, created DESC
				LIMIT 0,1";
		$uid = ($this->team) ? $this->tid : $this->u;
		$team = ($this->team) ? 1 : 0;
//		echo '<hr/>'.$this->tid.'~'.$this->iid.'~'.$this->cid.'~'.$team;
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->iid);
		$stmt->bindParam(2, $this->tid);
		$stmt->bindParam(3, $this->cid); // contest id
		$stmt->bindParam(4, $team); // is team
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row['compile_details']) {
			$row['tests'] = count(explode('|', $row['compile_details']));
		}
		if ($row['uid']) $row['submitter'] = $this->getUserInfo($row['uid']);
		return $row;
	}
	
	function addCmt ($line = -1, $content) {
		if ($line >= 0 && $content) {
			$query = "INSERT INTO
						" . $this->table_name . "_line_cmt
					SET
						sid = ?, line = ?, content = ?, uid = ?";

			$stmt = $this->conn->prepare($query);

			// posted values
			$content = content($content);

			// bind values
			$stmt->bindParam(1, $this->id);
			$stmt->bindParam(2, $line);
			$stmt->bindParam(3, $content);
			$stmt->bindParam(4, $this->u);

			if ($stmt->execute()) {
/*				$queryC = "SELECT id FROM
							" . $this->table_name . "_line_cmt
						WHERE 
							sid = ? AND line = ? AND content = ? AND uid = ?
						LIMIT 0,1";

				$stmtC = $this->conn->prepare($query);
				$stmtC->bindParam(1, $this->id);
				$stmtC->bindParam(2, $line);
				$stmtC->bindParam(3, $content);
				$stmtC->bindParam(4, $this->u);
				$stmtC->execute();

				$newCmt = $stmtC->fetch(PDO::FETCH_ASSOC);
				$newCmt['author'] = $this->getUserInfo();
*/
				$newCmt = array('sid' => $this->id, 'line' => $line, 'content' => $content, 'uid' => $this->u, 'author' => $this->getUserInfo(), 'created' => date("Y-m-d H:i:s"));
				$this->newCmt = $newCmt;
				return true;
			} else return false;
		}
	}
	
	function readAll ($u = null) {
		$cons = '';
		if ($this->team) $cons = 'AND tid = '.$this->tid;
		
		if ($this->iid && $u) $cond = "iid = ? AND uid = ? ".$cons; 
		else if ($this->iid) $cond = "iid = ? ".$cons; 
		else if ($u) $cond = "uid = ? ".$cons; 

		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE 
				{$cond}
				ORDER BY
					modified DESC, created DESC
				{$lim}";

		$stmt = $this->conn->prepare( $query );
		
		if ($this->iid && $u) {
			$stmt->bindParam(1, $this->iid);
			$stmt->bindParam(2, $this->iid);
		} else if ($this->iid) $stmt->bindParam(1, $this->iid);
		else if ($u) $stmt->bindParam(1, $u);
		
		$stmt->execute();

		$subsList = array();
		$score = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if (!$this->iid) {
				$row['pInfo'] = $this->sProbOne($row['iid']);
				$score += $row['score'];
			}
			$subsList[] = $row;
		}
		$rowsNum = $stmt->rowCount();
		$score = $score/$rowsNum;

		return $subsList;
	}
	
	function sProbOne ($iid) {
		$queryP = "SELECT code,title,tests FROM problems WHERE id = ? LIMIT 0,1";
		$stmtP = $this->conn->prepare($queryP);
		$stmtP->bindParam(1, $iid);
		$stmtP->execute();
		$row = $stmtP->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	// used for paging products
	public function countAll ($u) {
		if ($u) $cond = "WHERE uid = {$u}";
		$query = "SELECT id FROM " . $this->table_name . " {$cond}";

		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	public function readOne() {
//		echo $this->tests.'~~~';
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					id = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$row['author'] = $this->getUserInfo($row['uid']);
		$this->id = $row['id'];
		$this->file = $row['file'];
/*		$tdir = explode('/u', $this->file)[0].'/tests';
		$row['tests'] = count(array_diff(scandir($tdir), array('..', '.')));
*/		$row['compile_details'] = explode('|', $row['compile_details']);
		$row['console'] = json_decode($row['console']);
//		$row['tests'] = count($row['compile_details']);
		$row['pInfo'] = $pInfo = $this->sProbOne($row['iid']);
		$row['tests'] = $pInfo['tests'];

/*		$row['console_pla'] = json_decode($row['console_pla']);
		if ($row['similar']) {
			$similar = explode('|', $row['similar']);
			if (count($similar) > 0) {
				foreach ($similar as $sk => $simi) {
					if ($simi) {
						$simiAr = explode('::', $simi);
						$siu = preg_match('/u(.*)./', $simiAr[0]);
						$similar[$sk] = 
							array(
								'u' 	=> $siu,
								'file' 	=> $simiAr[0],
								'per' 	=> $simiAr[1]
							);
					}
				}
				$row['similar'] = $similar;
			}
		}
*/		
		$this->getCodeContent();
//		$this->codeContent = preg_replace('/\n/', '  ', $this->codeContent);
		$row['codeContent'] = preg_replace("/<br[^>]*>\s*\r*\n*/is", "\n", htmlentities($this->codeContent));

		$this->data = $row;
	}

	function cmtList () {
		$query = "SELECT * FROM
					" . $this->table_name . "_line_cmt
				WHERE 
					sid = ? 
				ORDER BY
					modified DESC, created DESC";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$subsList = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['author'] = $this->getUserInfo($row['uid']);
			$subsList[] = $row;
		}
		return $subsList;
	}
	
	public function listFiles ($u = null, $uType = true) {
		$this->_dir = $_dir = $this->codeDir.'/p'.$this->iid;
		if ($this->team) {
			$uType = false;
			if (!$u) $u = $this->tid;
			$this->_udir = $_udir = $this->_dir.'/ut'.$u;
		} else {
			if (!$u) $u = $this->u;
			$this->_udir = $_udir = $this->_dir.'/u'.$u;
		}
		
	//	$supported = array('cpp', 'c', 'java', '27.py', '32.py');
		$supported = array('cpp', 'c', 'java', 'py');
		$myCodeFile = array();
		foreach ($supported as $ext) {
			$ext = end(explode('.', $ext));
			$dirf = $_udir.'/'.$ext;
			if (is_dir($dirf)) {
				$filesAr = $this->getFiles($dirf, $ext, true);
				if (count($filesAr) > 0) $myCodeFile = array_merge($myCodeFile, $filesAr);
			}
		}
		return array_reverse($myCodeFile);
	}
	
	public function checkSubmit ($file = null, $u = null, $team = 0) {
		if (!$file) $file = $this->_file;
		$fullFilePath_ = MAIN_PATH.str_replace('./', '/', $file);
		$fullFilePath = MAIN_PATH.str_replace('./', '', $file);
		if (!$u) $u = $this->u;
		$team = ($this->team) ? 1 : 0;
//		$cond = ($this->team) ? "tid = ?" : "uid = ?";
		$query = "SELECT id FROM submissions WHERE (file = ? OR file = ? OR file = ?) AND uid = ? AND tid = ? AND team = ? AND submit = 1 LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $fullFilePath);
		$stmt->bindParam(2, $fullFilePath_);
		$stmt->bindParam(3, $file);
		$stmt->bindParam(4, $u);
		$stmt->bindParam(5, $this->tid);
		$stmt->bindParam(6, $team);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}

	function submit () {
		$console = $this->console;
		$consoleJSON = json_encode($console);
		$console['status'] = ($console['status'] == 'success') ? 0 : -1;
		foreach ($console['tests'] as $te) {
			$compAr[] = $te['checkTxt'];
			if ($te['checkTxt'] == 'AC') $ACar[] = $te['checkTxt'];
		}
		$AC = implode('|', $ACar);
		$comp = implode('|', $compAr);
		$ACnum = count($ACar);
		$score = round($ACnum/count($console['tests']) * 100);
		
		$team = ($this->team) ? 1 : 0;
		if (!$this->tid) $this->tid = 0;
		if (!$this->cid) $this->cid = 0;
//		echo $this->iid.'~~~'.$team.'~~~'.$this->u.'~~~'.$this->cid.'~~~'.$this->tid.'~~~'.$this->lang.'~~~'.$this->_file.'~~~'.$console['status'].'~~~'.$ACnum.'~~~'.$comp.'~~~'.$score.'~~~'.$consoleJSON;

		//write query
/*		$query = "INSERT INTO " . $this->table_name . " SET
					iid = ?, uid = ?, file = ?, lang = ?, compile_stt = ?, AC = ?, compile_details = ?, 
					score = ?, memory = ?, length = ?, time_taken = ?, similar = ?, similar_gg = ?, tokens = ?, lines = ?,
					created = ?";
*/		$query = "INSERT INTO " . $this->table_name . " SET
					iid = ?, uid = ?, file = ?, lang = ?, compile_stt = ?, AC = ?, compile_details = ?, 
					score = ?, console = ?, team = ?, tid = ?, cid = ? ";

		$stmt = $this->conn->prepare($query);
		// bind values
		$stmt->bindParam(1, $this->iid);
		$stmt->bindParam(2, $this->u);
		$stmt->bindParam(3, $this->_file);
		$stmt->bindParam(4, $this->lang);
		$stmt->bindParam(5, $console['status']);
		$stmt->bindParam(6, $ACnum);
		$stmt->bindParam(7, $comp);
		$stmt->bindParam(8, $score);
		$stmt->bindParam(9, $consoleJSON);
		$stmt->bindParam(10, $team);
		$stmt->bindParam(11, $this->tid);
		$stmt->bindParam(12, $this->cid);

		if ($stmt->execute()) {
			// update user statistics 
			$userData = $this->getUserData();
			$oldSubNum = $userData['submissions'];
			$userData['submissions']++;
			$userData['total_tests'] += count($console['tests']);
			$userData['AC'] += $ACnum;
			$userData['score'] = ($userData['score']*$oldSubNum + $score)/$userData['submissions'];
			if ($this->editUserData($userData)) {
				if ($this->team) {
					// update team statistics 
					$teamData = $this->getTeamData();
					$oldSubNum = $teamData['submissions'];
					$teamData['submissions']++;
					$teamData['total_tests'] += count($console['tests']);
					$teamData['AC'] += $ACnum;
					$teamData['score'] = ($teamData['score']*$oldSubNum + $score)/$teamData['submissions'];
					if ($this->editTeamData($teamData)) return true;
					else return false;
				}
			} else return false;
		} else return false;
	}
	
	function getTeamData () {
		$query = "SELECT submissions,rank,total_tests,score,AC,WA,RTE,DQ FROM team WHERE id = ? limit 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->tid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	function editTeamData ($valueAr) {
		$condAr = array();
		foreach ($valueAr as $vK => $oneField) {
			$oneField = htmlspecialchars(strip_tags($oneField));
			$condAr[] = "{$vK} = {$oneField}";
		}
		$cond = implode(', ', $condAr);
		
		$query = "UPDATE
					team
				SET
					{$cond}
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// bind parameters
		$stmt->bindParam(':id', $this->tid);

		// execute the query
		if ($stmt->execute()) return true; 
		else return false;
	}


	function editUserData ($valueAr, $u = null) {
		if (!$u) $u = $this->u;
		
		$condAr = array();
		foreach ($valueAr as $vK => $oneField) {
			$oneField = htmlspecialchars(strip_tags($oneField));
			$condAr[] = "{$vK} = {$oneField}";
		}
		$cond = implode(', ', $condAr);
		
		$query = "UPDATE
					members
				SET
					{$cond}
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// bind parameters
		$stmt->bindParam(':id', $u);

		// execute the query
		if ($stmt->execute()) return true; 
		else return false;
	}

	function getUserData ($u = null) {
		if (!$u) $u = $this->u;
		$query = "SELECT submissions,rank,total_tests,score,AC,WA,RTE,DQ FROM members WHERE id = ? OR username = ? limit 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $u);
		$stmt->bindParam(2, $u);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function getCodeContent ($file = null) {
		$this->ext = $this->mode = end(explode('.', $this->file));
		if ($this->ext == 'cpp') $this->mode = 'c_cpp';
		if (is_file($this->file)) $this->codeContent = file_get_contents($this->file);
		return $this->codeContent;
	}

}
?>
