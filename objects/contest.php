<?php
class Contest extends Problem {

	// database connection and table name
//	private $conn;
	private $table_name = "contests";
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

		// to get time-stamp for 'created' field
		parent::getTimestamp();

		//write query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					title = ?, code = ?, content = ?, uid = ?, problems = ?, test_time = ?, created = ?, modified = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->title=htmlspecialchars(strip_tags($this->title));
		$this->code = 'C-'.generateRandomString();
		$this->content = content($this->content);
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));

		// bind values
		$stmt->bindParam(1, $this->title);
		$stmt->bindParam(2, $this->code);
		$stmt->bindParam(3, $this->content);
		$stmt->bindParam(4, $this->u);
		$stmt->bindParam(5, $this->problems);
		$stmt->bindParam(6, $this->test_time);
		$stmt->bindParam(7, $this->timestamp);
		$stmt->bindParam(8, $this->timestamp);

		if ($stmt->execute()) {
			$contestNew = $this->sReadOne();
			if ($this->id) {
				$this->contestNew = $contestNew;
				return true;
			} else return false;
		} else {
			return false;
		}
	}

	function getDiscussions () {
		$query = "SELECT * FROM
					" . $this->table_name . "_discussions
				WHERE
					cid = ?
				ORDER BY
					modified DESC, created DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$dcList = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['author'] = $this->getUserInfo($row['uid']);
			$dcList[] = $row;
		}
		return $dcList;
	}
	
	function getOneDiscussion () {
		$query = "SELECT * FROM
					" . $this->table_name . "_discussions
				WHERE
					id = ?
				LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->did);
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row['author'] = $this->getUserInfo($row['uid']);
		return $row;
	}
	
	function getDiscussionReplies () {
		$query = "SELECT * FROM
					" . $this->table_name . "_discussions_reply
				WHERE
					cid = ? AND did = ?
				ORDER BY
					modified DESC, created DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->did);
		$stmt->execute();
		$dcRepList = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['author'] = $this->getUserInfo($row['uid']);
			$dcRepList[] = $row;
		}
		return $dcRepList;
	}

	function addDiscussionsReply () {
		//write query
		$query = "INSERT INTO
					" . $this->table_name . "_discussions_reply
				SET
					content = ?, cid = ?, did = ?, uid = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->content= content($this->replycontent);

		// bind values
		$stmt->bindParam(1, $this->content);
		$stmt->bindParam(2, $this->id);
		$stmt->bindParam(3, $this->did);
		$stmt->bindParam(4, $this->u);

		if ($stmt->execute()) {
			return true;
		} else return false;
	}
    
	function addDiscussions () {
		//write query
		$query = "INSERT INTO
					" . $this->table_name . "_discussions
				SET
					title = ?, content = ?, cid = ?, uid = ?";

		$stmt = $this->conn->prepare($query);

		// posted values
		$this->dTitle= htmlspecialchars(strip_tags($this->dTitle));
		$this->dContent= content($this->dContent);

		// bind values
		$stmt->bindParam(1, $this->dTitle);
		$stmt->bindParam(2, $this->dContent);
		$stmt->bindParam(3, $this->id);
		$stmt->bindParam(4, $this->u);

		if ($stmt->execute()) {
			$newD = $this->sDiscussionsReadOne();
			$this->did = $newD['id'];
			return true;
		} else return false;
	}
    
	function sDiscussionsReadOne () {
		$query = "SELECT id FROM
					" . $this->table_name . "_discussions
				WHERE
					title = ?
				LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->dTitle);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row['id']) return $row;
		else return false;
	}
	
	
	function joinTest ($teamID) {
		$isTeam = ($teamID) ? true : false;
		$query = "INSERT INTO
					" . $this->table_name . "_join
				SET
					uid = ?, tid = ?, cid = ?";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->u);
		$stmt->bindParam(2, $teamID);
		$stmt->bindParam(3, $this->id);
//		echo $this->u.'~'.$teamID.'~'.$this->id;

		if ($stmt->execute()) {
			$_SESSION['c'.$this->id.'_uid'] = $uid;
			$_SESSION['c'.$this->id.'_team'] = $team;
			return true;
		} else return false;
	}
	function outTest ($teamID = null) {
		$isTeam = ($teamID) ? true : false;

		$query = "DELETE FROM " . $this->table_name . "_join WHERE uid = ? AND tid = ? AND cid = ?";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->u);
		$stmt->bindParam(2, $teamID);
		$stmt->bindParam(3, $this->id);

		if ($delete = $stmt->execute()) {
			$_SESSION['c'.$this->id.'_uid'] = null;
			$_SESSION['c'.$this->id.'_team'] = false;
			return true;
		} else return false;
	}
	
	function getMyTeamInfo () {
		$this->uid = $_SESSION['c'.$this->id.'_uid'];
		$this->team = $_SESSION['c'.$this->id.'_team'];
		$query = "SELECT tid,score FROM " . $this->table_name . "_join WHERE uid = ? AND cid = ? LIMIT 0,1";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->u);
		$stmt->bindParam(2, $this->id);

		$stmt->execute();
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$this->uid = $_SESSION['c'.$this->id.'_uid'] = $row['tid'];
			$this->team = $_SESSION['c'.$this->id.'_team'] = ($row['tid']) ? true : false;
			return true;
		} else return false;
	}
	
	function sReadOne () {
		$query = "SELECT id,code FROM
					" . $this->table_name . "
				WHERE
					id = ? OR code = ? OR title = ?
				LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->id);
		$stmt->bindParam(3, $this->title);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		$this->link = $row['link'] = $this->cLink.'/'.$row['code'];
		return $row;
	}
	
	function readAll ($page = null, $from_record_num = null, $records_per_page = null) {
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
			$row['link'] = $this->cLink.'/'.$row['code'];
			$row['author'] = $this->getUserInfo($row['uid']);
			
			$row['scoreTxtCorlor'] = '';
			if ($row['score'] >= 80) $row['scoreTxtCorlor'] = 'success';
			else if ($row['score'] >= 50) $row['scoreTxtCorlor'] = 'warning';
			
			$row['problems'] = explode(',', $row['problems']);

			$test_time_min = $row['test_time_min'] = (int)$row['test_time'];
			$test_time[0] = floor($row['test_time']/60);
			$test_time[1] = $row['test_time']%60;
			$row['test_time'] = ($test_time[0] > 0) ? "{$test_time[0]}h{$test_time[1]}'" : "{$test_time[1]}'";
			$test_start = $row['test_start'];
			$tp_start = strtotime($test_start);
			$tp_end = $tp_start+(60*$test_time_min);
			$test_end = $row['test_end'] = date("Y-m-d H:i:s", $tp_end);

			$tp_now = strtotime('now');

			if ($tp_end <= $tp_now) {
				$row['timeOut'] = true;
				$row['stt'] = -1;
				$row['status'] = '<div class="text-danger">Time out!</div>';
			} else if ($tp_start > $tp_now) {
				$row['timeOut'] = false;
				$row['stt'] = 0;
				$d_start = new DateTime($test_start);
				$d_now = new DateTime("now");
				$diff = $d_now->diff($d_start);

				if ($diff->d) $row['status'] = '<div class="text-default">'.$diff->d.' days till start</div>';
				else if ($diff->h) $row['status'] = '<div class="text-default">'.$diff->h.' hours till start</div>';
				else if ($diff->i) $row['status'] = '<div class="text-default">'.$diff->i.' mins till start</div>';
				else if ($diff->s) $row['status'] = '<div class="text-default">'.$diff->s.' secs till start</div>';
				//$row['status'] = '<div class="text-default">Not started</div>';
			} else {
				$row['timeOut'] = false;
				$row['stt'] = 1;
				$d_end = new DateTime($test_end);
				$d_now = new DateTime("now");
				$diff = $d_end->diff($d_now);

				if ($diff->h) $row['status'] = '<div class="text-success">'.$diff->h.' hours left</div>';
				else if ($diff->i) $row['status'] = '<div class="text-success">'.$diff->i.' mins left</div>';
				else if ($diff->s) $row['status'] = '<div class="text-success">'.$diff->s.' secs left</div>';
				//$row['status'] = '<div class="text-success">Running...</div>';
			}
			
			$this->cList[] = $row;
		}

		return $this->cList;
	}
	
	public function countAll(){

		$query = "SELECT id FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	function readOne () {
		$query = "SELECT * FROM
					" . $this->table_name . "
				WHERE
					id = ? OR code = ? OR title = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->id);
		$stmt->bindParam(3, $this->title);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row['id'];
		$this->title = $row['title'];
		$this->code = $row['code'];
		$this->link = $row['link'] = $this->cLink.'/'.$row['code'];
		$this->content = $row['content'];
		$this->views = $row['views'];
//		$this->uid = $row['uid'];
		$this->test_time_min = $row['test_time_min'] = (int)$row['test_time'];
		$test_time[0] = floor($row['test_time']/60);
		$test_time[1] = $row['test_time']%60;
		$this->test_time = $row['test_time'] = $test_time;
		$this->test_start = $row['test_start'];
		$tp_start = strtotime($this->test_start);
		$tp_end = $tp_start+(60*$this->test_time_min);
		$this->test_end = $row['test_end'] = date("Y-m-d H:i:s", $tp_end);
		
		// get join user/team info
		$this->getMyTeamInfo();
		
		$problems = explode(',', $row['problems']);
		$qP = "SELECT code,title FROM problems WHERE id = ? LIMIT 0,1";
		$sP = $this->conn->prepare($qP);
		foreach ($problems as $k => $p) {
			$sP->bindParam(1, $p);
			$sP->execute();
			$pO = $sP->fetch(PDO::FETCH_ASSOC);
			$pO['id'] = $p;
			$pO['link'] = $this->cLink.'/'.$row['code'].'/'.$pO['code'];
			$problems[$k] = $pO;
		}
		$this->problems = $row['problems'] = $problems;
		
		$this->author = $row['author'] = $this->getUserInfo($this->uid);
				
/*		$this->pDir = MAIN_PATH.'/data/code/p'.$this->id;
		$this->pTestDir = $this->pDir.'/tests';

		$this->input = file_get_contents($this->pTestDir.'/1/test.in.txt');
		$this->output = file_get_contents($this->pTestDir.'/1/test.out.txt');
		
		$this->mySubmitCount = $row['mySubmitCount'] = $this->checkMySubmissions();

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
