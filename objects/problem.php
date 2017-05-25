<?php
class Problem extends Config {

	// database connection and table name
//	private $conn;
	private $table_name = "problems";
	private $submissions_table_name = "submissions";

	// object properties
	public $id;
	public $title;
	public $code;
	public $content;
	public $cid;
	public $uid;
	public $input;
	public $output;
	public $score;
	public $time_limit;
	public $memory_limit;
	public $tests;
	public $views;
	public $submissions;
	public $mySubmitCount;
	public $author;
	public $sid;
	public $topSubmissions;

	public function __construct() {
		parent::__construct();
	}

	// create product
	function create() {
		//write query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					title = ?, code = ?, content = ?, uid = ?, 
					input = ?, output = ?, score = ?, time_limit = ?, memory_limit = ?, 
					color = ?, stt = ?, lang = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->title=htmlspecialchars(strip_tags($this->title));
		$this->code = generateRandomString();
		$this->content = content($this->content);
		$this->input = htmlspecialchars(strip_tags($this->in_type));
		$this->output = htmlspecialchars(strip_tags($this->out_type));
		$this->score=htmlspecialchars(strip_tags($this->score_type));
		$this->time_limit=htmlspecialchars(strip_tags($this->time_limit));
		$this->memory_limit=htmlspecialchars(strip_tags($this->memory_limit));
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
		$this->color = random_color();
		$this->lang = htmlspecialchars(strip_tags($this->lang));
		$this->stt = htmlspecialchars(strip_tags($this->stt));

		// bind values
		$stmt->bindParam(1, $this->title);
		$stmt->bindParam(2, $this->code);
		$stmt->bindParam(3, $this->content);
		$stmt->bindParam(4, $this->u);
		$stmt->bindParam(5, $this->input);
		$stmt->bindParam(6, $this->output);
		$stmt->bindParam(7, $this->score);
		$stmt->bindParam(8, $this->time_limit);
		$stmt->bindParam(9, $this->memory_limit);
		$stmt->bindParam(10, $this->color);
		$stmt->bindParam(11, $this->stt);
		$stmt->bindParam(12, $this->lang);

		//echo 'Title: '.$this->title.'<br/>Code: '.$this->code.'<br/>Content: '.$this->content.'<br/>User: '.$this->u.'<br/>Input: '.$this->input.'<br/>Output: '.$this->output.'<br/>Score: '.$this->score.'<br/>Time: '.$this->time_limit.'<br/>Mem: '.$this->memory_limit.'<br/>Color: '.$this->color.'<br/>Stt: '.$this->stt.'<br/>Lang: '.$this->lang;
		
		if ($stmt->execute()) {
			$prob = $this->sReadOne();
			if ($this->id) {
				if ($this->in_num) { // generate input
					$this->generateInput();
				}
				$upTest = $this->upTest();
				if ($upTest) {
					$this->problemNew = $prob;
					return true;
				} else return false;
			} else return false;
		} else 
			return false;
	}

	function edit () {
		// to get time-stamp for 'created' field
		parent::getTimestamp();

		//write query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					title = ?, modified = ?, content = ?, uid = ?, 
					input = ?, output = ?, score = ?, time_limit = ?, memory_limit = ?, lang = ?
				WHERE 
					id = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->title=htmlspecialchars(strip_tags($this->title));
		$this->content = content($this->content);
		$this->input = htmlspecialchars(strip_tags($this->in_type));
		$this->output = htmlspecialchars(strip_tags($this->out_type));
		$this->score=htmlspecialchars(strip_tags($this->score_type));
		$this->time_limit=htmlspecialchars(strip_tags($this->time_limit));
		$this->memory_limit=htmlspecialchars(strip_tags($this->memory_limit));
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
		$this->lang = htmlspecialchars(strip_tags($this->lang));

		// bind values
		$stmt->bindParam(1, $this->title);
		$stmt->bindParam(2, $this->timestamp);
		$stmt->bindParam(3, $this->content);
		$stmt->bindParam(4, $this->u);
		$stmt->bindParam(5, $this->input);
		$stmt->bindParam(6, $this->output);
		$stmt->bindParam(7, $this->score);
		$stmt->bindParam(8, $this->time_limit);
		$stmt->bindParam(9, $this->memory_limit);
		$stmt->bindParam(10, $this->lang);
		$stmt->bindParam(11, $this->id);

		//echo 'Title: '.$this->title.'<br/>Code: '.$this->code.'<br/>Content: '.$this->content.'<br/>User: '.$this->u.'<br/>Input: '.$this->input.'<br/>Output: '.$this->output.'<br/>Score: '.$this->score.'<br/>Time: '.$this->time_limit.'<br/>Mem: '.$this->memory_limit.'<br/>Color: '.$this->color.'<br/>Stt: '.$this->stt.'<br/>Lang: '.$this->lang;
		
		if ($stmt->execute()) {
			if ($this->upfile['name']) {
				echo 'disssssssss';
				$upTest = $this->upTest();
				if ($upTest) {
					return true;
				} else return false;
			} else return true;
		} else {
			return false;
		}
	}

	function upTest () {
		$upfile = $this->upfile;
		$maxSize = $this->maxSize;
		
		$ext = end(explode('.', $upfile['name']));
		
		$pDir = MAIN_PATH.'/data/code/p'.$this->id;
		$pTestDir = $pDir.'/tests';
		if (!is_dir($pDir)) {
			$mkdir = mkdir($pDir, 0777);
			chmod($pDir, 0777);
			if ($mkdir) {
				$mkdir = mkdir($pTestDir, 0777);
				chmod($pTestDir, 0777);
			}
		}
		$moveDir = $pTestDir.'/tests.'.$ext;

		try {
			
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (
				!isset($upfile['error']) ||
				is_array($upfile['error'])
			) {
				throw new RuntimeException('Invalid parameters.');
			}

			// Check $upfile['error'] value.
			switch ($upfile['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('No file sent.');
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException('Exceeded filesize limit.');
				default:
					throw new RuntimeException('Unknown errors.');
			}

			// You should also check filesize here. 
			if ($upfile['size'] > $maxSize) {
				throw new RuntimeException('Exceeded filesize limit.');
			}

			// DO NOT TRUST $upfile['mime'] VALUE !!
			// Check MIME Type by yourself.
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === array_search(
				$finfo->file($upfile['tmp_name']),
				array(
					'application/x-rar-compressed',
					'application/octet-stream',
					'application/zip',
				),
				true
			)) {
				throw new RuntimeException('Invalid file format.');
			}

			// You should name it uniquely.
			// DO NOT USE $upfile['name'] WITHOUT ANY VALIDATION !!
			// On this example, obtain safe unique name from its binary data.
			if (!move_uploaded_file(
				$upfile['tmp_name'], $moveDir
			)) {
				throw new RuntimeException('Failed to move uploaded file.');
			}

			chmod($moveDir, 0777);
			
			$zip = new ZipArchive;
			if ($zip->open($moveDir) === TRUE) {
				$zip->extractTo($pTestDir);
				$zip->close();
				chmod($pTestDir, 0777);
				exec ("find {$pTestDir} -type d -exec chmod 0777 {} +");
				exec ("find {$pTestDir} -type f -exec chmod 0777 {} +");
				
				// count folders in tests folder to update tests field
				// [code here]
				
				// if there is 1/test.out.txt => no need to generate output
				if (file_exists($pTestDir.'/1/test.out.txt')) {
					// count tests num
					$tDir = new DirectoryIterator($pTestDir);
					$testsNum = 0;
					foreach ($tDir as $oneTestFolder) {
						if ($oneTestFolder->isDir() && !$oneTestFolder->isDot()) 
							$testsNum++;
					}
				} else {
					// check if there is standard code => generate output
					$extAr = array('cpp', 'py', 'c', 'java');
					$testDir = $pTestDir;
					foreach ($extAr as $ext) {
						if (file_exists($pTestDir.'/standard.'.$ext)) {
							$compile = new sCompileClass();
							$codeFile = $pTestDir.'/standard.'.$ext;
							// check if there is input.txt file => generate input folder
							if (file_exists($pTestDir.'/input.txt')) {
								$inputFile = $testDir.'/input.txt';
								$inputs = file_get_contents($inputFile);
								$inputAr = array_filter(array_values(explode('#!end!#', $inputs)));
								// make input folder
								$testsNum = 0;
								foreach ($inputAr as $iK => $oneInp) {
									$k = $iK+1;
									$oneTestFolder = $testDir.'/'.$k;
									mkdir($oneTestFolder);
									chmod($oneTestFolder, 0777);
									
									$inFile = $oneTestFolder.'/test.in.txt';
									file_put_contents($inFile, $oneInp);

									$output = $compile->sCompile($codeFile, $inFile, $k);
								//	echo $output;
									
									$outFile = $oneTestFolder.'/test.out.txt';
									//file_put_contents($outFile, $output);
									$testsNum++;
								}
							} else {
								$tDir = new DirectoryIterator($testDir);
								$testsNum = 0;
								foreach ($tDir as $oneTestFolder) {
									if ($oneTestFolder->isDir() && !$oneTestFolder->isDot()) {
										$k = $oneTestFolder->getFilename();
										$inFile = $oneTestFolder.'/test.in.txt';
										$output = $compile->sCompile($codeFile, $inFile, $k);
										$outFile = $oneTestFolder.'/test.out.txt';
										//file_put_contents($outFile, $output);
										$testsNum++;
									}
								}
							}
							break;
						}
					}
				}
				
				// change test numbers
				$query = "UPDATE " . $this->table_name . " SET
							tests  = :tests
						WHERE
							id = :id";

				$stmt = $this->conn->prepare($query);

				// bind parameters
				$stmt->bindParam(':tests', $testsNum);
				$stmt->bindParam(':id', $this->id);

				// execute the query
				if ($stmt->execute()) return true;
				else return false;

			} else {
				echo 'unzip error';
				return false;
			}

		} catch (RuntimeException $e) {

			echo $e->getMessage();
			
			return false;
		}

	}

/*	function generateInput () {
		
	}
*/	
	function sReadOne () {
		$query = "SELECT id,code FROM
					" . $this->table_name . "
				WHERE
					code = ? OR title = ?
				LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->title);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		$this->link = $row['link'] = $this->pLink.'/'.$row['code'];
		return $row;
	}
	
	function readAll ($page = null, $from_record_num = 0, $records_per_page = 0) {
		$lim = '';
		if ($from_record_num) $lim = "LIMIT
					{$from_record_num}, {$records_per_page}";

		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					(stt != -1 AND stt != 1) 
					OR
						uid = {$this->u}
					OR
						{$this->me['is_mod']} = 1
				ORDER BY
					modified DESC, created DESC
				{$lim}";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->pLink.'/'.$row['code'];
			$row['author'] = $this->getUserInfo($row['uid']);
			
			$row['scoreTxtCorlor'] = '';
			if ($row['score'] >= 80) $row['scoreTxtCorlor'] = 'success';
			else if ($row['score'] >= 50) $row['scoreTxtCorlor'] = 'warning';
			
			$numSub = $this->getDifficulty($row['id'], $row['tests']);
			$row['totalAC'] = $this->totalAC;
			$row['totalTests'] = $this->totalTests = $row['tests'] * $numSub;
			if ($numSub == 0) $row['per'] = 0;
			else $row['per'] = round($row['totalAC']/$row['totalTests'] * 100, 2);
			
			if ($row['per'] > 80) $row['perCls'] = 'success';
			
			//$row['cat'] = $this->getCat($row['cid']);
			$row['langTxt'] = str_replace(array('|', 'cpp', 'c', 'python', 'java'), array(', ', 'C++', 'C', 'Python', 'Java'), $row['lang']);
			$row['lang'] = explode('|', $row['lang']);
			
			$this->problemsList[] = $row;
		}

		return $stmt;
	}
	
	function readForTest ($u = null) {
		if ($u == -1) $cond = 'uid != '.$this->u;
		else $cond = 'uid = '.$this->u;
		$query = "SELECT
					id,code,title
				FROM
					" . $this->table_name . "
				WHERE
					stt = 1 AND {$cond}
				ORDER BY
					modified DESC, created DESC";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$pAr = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// if a test used this problem already then remove it from list
			$pid = $row['id'];

			$querys = "SELECT id FROM contests WHERE problems = '{$pid}' OR problems LIKE '%,{$pid}' OR problems LIKE '{$pid},%' OR problems LIKE '%,{$pid},%'";
			$stmtCount = $this->conn->prepare($querys);
			$stmtCount->execute();
			$num = $stmtCount->rowCount();

			$row['uses'] = $num;
			//if (!$this->isUsed($pid)) $pAr[] = $row;
			$pAr[] = $row;
		}

		return $pAr;
	}
	
	public function isUsed ($pid) {
		$querys = "SELECT id FROM contests WHERE problems = '{$pid}' OR problems LIKE '%,{$pid}' OR problems LIKE '{$pid},%' OR problems LIKE '%,{$pid},%'";
		$stmtCount = $this->conn->prepare($querys);
		$stmtCount->execute();
		$num = $stmtCount->rowCount();
		if ($num <= 0) return false;
		return true;
	}
	
	public function getCat ($id) {
		$query = "SELECT * FROM categories WHERE id = ?";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	public function getDifficulty ($id, $tests) {
//		$query = "SELECT * FROM submissions WHERE iid = ? AND team = 0";
		$query = "SELECT * FROM submissions WHERE iid = ?";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $id);
		$stmt->execute();

		$this->totalAC = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			$this->totalAC += $row['AC'];
		return $stmt->rowCount();
	}
	
	public function countAll () {

		$query = "SELECT id FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	function readOne ($isContest = false) {
		if ($isContest) $condInProblem = "AND stt != -1";
		else $condInProblem = "AND ((stt != -1 AND stt != 1) 
				OR
					uid = {$this->u}
				OR
					{$this->me['is_mod']} = 1)";
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					(code = ? OR title = ?) {$condInProblem}
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->title);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row['id'];
		$this->stt = $row['stt'];
		$this->title = $row['title'];
		$this->code = $row['code'];
		$this->link = $this->pLink.'/'.$row['code'];
		$this->content = $row['content'];
		$this->cid = $row['cid'];
		$this->inputType = $row['input'];
		$this->outputType = $row['output'];
		$this->score = $row['score'];
		$this->time_limit = $row['time_limit'];
		$this->memory_limit = $row['memory_limit'];
		$this->tests = $row['tests'];
		$this->views = $row['views'];
		$this->uid = $row['uid'];
		
		$this->pDir = MAIN_PATH.'/data/code/p'.$this->id;
		$this->pTestDir = $this->pDir.'/tests';

		$this->input = file_get_contents($this->pTestDir.'/1/test.in.txt');
		$this->output = file_get_contents($this->pTestDir.'/1/test.out.txt');
		
		$this->author = $row['author'] = $this->getUserInfo($this->uid);
		$this->lang = $row['lang'] = explode('|', $row['lang']);
				
/*		$this->mySubmitCount = $row['mySubmitCount'] = $this->checkMySubmissions();

		$this->topSubmissions();
		$this->submissions = $this->countSubmissions();

		$numSub = $this->getDifficulty($row['id'], $row['tests']);
		$row['totalAC'] = $this->totalAC;
		$row['totalTests'] = $this->totalTests = $row['tests'] * $numSub;
		if ($numSub == 0) $row['ACper'] = 0;
		else $row['ACper'] = round($row['totalAC']/$row['totalTests'] * 100, 2);
		$this->ACper = $row['ACper'];
		$this->WAper = 100 - $this->ACper;
*/
		return $row;
	}
	
	public function countSubmissions () {
		$query = "SELECT id FROM " . $this->submissions_table_name . " WHERE iid = ?";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	public function topSubmissions ($from_record_num = 0, $records_per_page = 5) {
		$query = "SELECT * FROM " . $this->submissions_table_name . "
				WHERE 
					iid = ? AND team = 0 AND score > 0
				ORDER BY
					score DESC, modified DESC, created DESC
				LIMIT
					{$from_record_num}, {$records_per_page}";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['author'] = $this->getUserInfo($row['uid']);
			$row['scoreTxtCorlor'] = '';
			if ($row['score'] >= 80) $row['scoreTxtCorlor'] = 'success';
			else if ($row['score'] >= 50) $row['scoreTxtCorlor'] = 'warning';
			$this->topSubmissions[] = $row;
		}

		return $this->topSubmissions;
	}

	public function getSubmissions () {
		$query = "SELECT
					*
				FROM
					" . $this->submissions_table_name . "
				WHERE
					iid = ?
				ORDER BY
					score DESC, modified DESC, created DESC";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['author'] = $this->getUserInfo($row['uid']);
			$row['compile_details'] = explode('|', $row['compile_details']);
			$row['scoreTxtCorlor'] = '';
			if ($row['score'] >= 80) $row['scoreTxtCorlor'] = 'success';
			else if ($row['score'] >= 50) $row['scoreTxtCorlor'] = 'warning';
			$this->subsList[] = $row;
		}

		return $this->subsList;
	}

	public function checkMySubmissions () {
		$query = "SELECT id FROM " . $this->submissions_table_name . " WHERE iid = ? AND uid = ? ";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->u);
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	public function readAllSubMy ($from_record_num = 0, $records_per_page = 5) {
		$query = "SELECT
					*
				FROM
					" . $this->submissions_table_name . "
				WHERE 
					iid = ? AND uid = ?
				ORDER BY
					modified DESC, created DESC
				LIMIT
					{$from_record_num}, {$records_per_page}";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->u);
		$stmt->execute();
		
		return $stmt;
	}
	
	function changeStt ($stt) {
		$query = "UPDATE
					" . $this->table_name . "
				SET
					stt  = :stt
				WHERE
					id = :id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':stt', $stt);
		$stmt->bindParam(':id', $this->id);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}
	
	function update ($valAr, $id = null) {
		if (!$id) $id = $this->id;
		foreach ($valAr as $key => $oV) {
			$ar[] = $key.' = ?';
		}
		$str = implode(', ', $ar);
		$query = "UPDATE " . $this->table_name . " SET {$str} WHERE id = {$id}";

		$stmt = $this->conn->prepare($query);
		$k = 0;
		foreach ($valAr as $key => $oV) {
			$k++;
			$stmt->bindParam($k, $oV);
		}
		// execute the query
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	// delete the product
	function delete () {

		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);

		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

}
?>
