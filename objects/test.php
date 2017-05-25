<?php
class Test extends Config {

	// database connection and table name
//	private $conn;
	private $table_name = "test";

	// object properties
	public $id;
	public $title;
	public $link;
	public $content;
	public $pid;
	public $uid;
	public $teamID;
	public $views;
	public $author;

	public function __construct() {
		parent::__construct();
	}

	// create product
	function create() {

		// to get time-stamp for 'created' field
		parent::getTimestamp();

		//write query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					title = ?, link = ?, content = ?, pid = ?, uid = ?, 
					time = ?, created = ?, modified = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->title=htmlspecialchars(strip_tags($this->title));
		$this->code = generateRandomString();
		$this->content = content($this->content);
		$this->input=htmlspecialchars(strip_tags($this->input));
		$this->output=htmlspecialchars(strip_tags($this->output));
		$this->score=htmlspecialchars(strip_tags($this->score));
		$this->time_limit=htmlspecialchars(strip_tags($this->time_limit));
		$this->memory_limit=htmlspecialchars(strip_tags($this->memory_limit));
		$this->tests=htmlspecialchars(strip_tags($this->tests));
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
		$this->color = random_color();

		// bind values
		$stmt->bindParam(1, $this->title);
		$stmt->bindParam(2, $this->code);
		$stmt->bindParam(3, $this->content);
		$stmt->bindParam(4, $this->cid);
		$stmt->bindParam(5, $this->u);
		$stmt->bindParam(6, $this->input);
		$stmt->bindParam(7, $this->output);
		$stmt->bindParam(8, $this->score);
		$stmt->bindParam(9, $this->time_limit);
		$stmt->bindParam(10, $this->memory_limit);
		$stmt->bindParam(11, $this->tests);
		$stmt->bindParam(12, $this->color);
		$stmt->bindParam(13, $this->timestamp);
		$stmt->bindParam(14, $this->timestamp);

		if ($stmt->execute()) {
			$prob = $this->readOne();
			if ($this->id) {
				$upTest = $this->upTest();
				if ($upTest) return true;
				else return false;
			} else return false;
		} else {
			return false;
		}
	}

	function readAll ($page, $from_record_num, $records_per_page) {
		$lim = '';
		if ($from_record_num) $lim = "LIMIT
					{$from_record_num}, {$records_per_page}";

		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				ORDER BY
					modified DESC, created DESC
				{$lim}";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->tLink.'/'.$row['link'];
			$row['author'] = $this->getUserInfo($row['uid']);
			$row['pID'] = explode(',', $row['pid']);
			$row['pNum'] = count($row['pID']);
			$this->testsList[] = $row;
		}

		return $stmt;
	}
	
	public function countAll () {

		$query = "SELECT id FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	function readOne () {
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					id = ? OR link = ? OR title = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->id);
		$stmt->bindParam(3, $this->title);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$row['pID'] = $this->pID = explode(',', $row['pid']);
		$row['pNum'] = $this->pNum = count($row['pID']);
		foreach ($row['pID'] as $k => $pO) {
			$this->p = $pO;
			$pList = $this->getProb();
			foreach ($pList as $pk => $po) {
//				if ($pk == 'color' || $pk == 'code' || $pk == 'id') $this->pListTab[$k][$pk] = $po;
				$this->pListTab[$k][$pk] = $po;
//				$this->pList[$k][$pk] = $po;
			}
/*			if ($this->pCurrent && $this->pCurrent == $pList['code']) {
				$this->pIn = $this->pList[$k];
				$this->pIn['file'] = $this->file;
			}
*/		}
				
//		$row['pList'] = $this->pList;
		$row['pListTab'] = $this->pListTab;
//		if ($this->pCurrent) $row['pIn'] = $this->pIn;

		$this->id = $row['id'];
		$this->title = $row['title'];
		$this->link = $row['link'] = $this->tLink.'/'.$row['link'];
		$this->content = $row['content'];
		$this->pid = $row['pid'];
		$this->time = $row['time'];
		$this->views = $row['views'];
		$this->uid = $row['uid'];
		
		$this->author = $row['author'] = $this->getUserInfo($this->uid);

		return $row;
	}
	
	function scoreboard () {
		$query = "SELECT * FROM test_submit WHERE iid = ? ";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$stmt = $this->conn->prepare("SELECT title,id FROM team WHERE id = {$row['tid']} ");
			$stmt->execute();
			$teamIn = $stmt->fetch(PDO::FETCH_ASSOC);
			$row['team'] = array('title' => $teamIn['title'], 'link' => $this->tmLink.'/'.$teamIn['id']);
			
			$totalProb = $totalScore = 0;
			foreach ($this->pListTab as $pO) {
				$totalProb++;
				$probDt = $row['details'][$pO['id']] = $this->getSubmitProb($pO['id']);
				if ($probDt) {
					$row['details'][$pO['id']]['tests'] = count(explode('|', $probDt['compile_details']));
					$totalScore += $probDt['score'];
				}
			}
			$row['score'] = round($totalScore/$totalProb, 2);
			
			$this->scoreboard[] = $row;
		}

	}
	
	function getSubmitProb ($p) {
		$query = "SELECT * FROM submissions WHERE iid = ? AND uid = ? AND team = 1 LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $p);
		$stmt->bindParam(2, $this->teamID);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row;
	}
	
	function getProb () {
		$query = "SELECT id,code,color FROM problems WHERE id = ? LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->p);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	function getProbDetails () {
		$query = "SELECT * FROM problems WHERE code = ? LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->pCurrent);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$row['pDir'] = MAIN_PATH.'/data/code/p'.$row['id'];
			$row['pTestDir'] = $row['pDir'].'/tests';

			$row['input'] = file_get_contents($row['pTestDir'].'/1/test.in.txt');
			$row['output'] = file_get_contents($row['pTestDir'].'/1/test.out.txt');
			
			$ext = 'cpp';
			if ($ext == 'cpp') $mode = 'c_cpp';
			$this->_dir = $_dir = $this->codeDir.'/p'.$this->p;
			$this->_udir = $_udir = $this->_dir.'/ut'.$this->teamID;
			$this->_workingDir = $_workingDir = $this->_udir.'/'.$ext.'/0.'.$ext;
			$this->file = Array (
				'dir' => $_workingDir,
				'u' => 1, 
				'filename' => 0,
				'submit' => 0,
				//'compile_stt' => 'WA',
				'ext' => $ext,
				'mode' => $mode
			);
		
		$this->pIn = $row;
		$this->pIn['file'] = $this->file;

		return $row;
	}
	
	function update() {

		$query = "UPDATE
					" . $this->table_name . "
				SET
					name = :name,
					price = :price,
					description = :description,
					category_id  = :category_id
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind parameters
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':category_id', $this->category_id);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// delete the product
	function delete() {

		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);

		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

}
?>
