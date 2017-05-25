<?php
class Search extends Config {

	public function __construct() {
		parent::__construct();
	}

	function search ($type = null) {
		$this->response['problems'] = $this->searchProblems();
		$this->response['contests'] = $this->searchContests();
		$this->response['topics'] = $this->searchTopics();
	}
	
	function searchProblems () {
		$query = "SELECT * FROM problems
				WHERE 
					INSTR(`title`, '{$this->keyword}') > 0
				ORDER BY
					modified DESC, created DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->keyword);
		$stmt->execute();
		$ar = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->pLink.'/'.$row['code'];
			$row['author'] = $this->getUserInfo($row['uid']);
			$row['langTxt'] = str_replace(array('|', 'cpp', 'c', 'python', 'java'), array(', ', 'C++', 'C', 'Python', 'Java'), $row['lang']);
			$row['lang'] = explode('|', $row['lang']);
			$ar[] = $row;
		}
		return $ar;
	}
	
	function searchContests () {
		$query = "SELECT * FROM contests
				WHERE 
					INSTR(`title`, '{$this->keyword}') > 0
				ORDER BY
					modified DESC, created DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->keyword);
		$stmt->execute();
		$ar = array();
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
			$ar[] = $row;
		}
		return $ar;
	}
	
	function searchTopics () {
		$query = "SELECT * FROM topic
				WHERE 
					INSTR(`title`, '{$this->keyword}') > 0
				ORDER BY
					modified DESC, created DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->keyword);
		$stmt->execute();

		$ar = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['posts'] = $row['replies'] + 1;
			if (!$or) $row['link'] .= '#'.$row['posts'];
			$row['author'] = $this->getUserInfo($row['uid']);

			// this for forum
			if ($row['replies'] > 0) $lastPost = $this->getLastPost($row['id']);
			else $lastPost = array('uid' => $row['uid'], 'created' => $row['created'], 'author' => $row['author']);
			$row['lastpost'] = $lastPost;

			$row['cat'] = $cat = $this->getForum($row['fid']);

//			$row['link'] = $this->bLink.'/'.$cat['link'].'/'.$row['link'];
			$row['link'] = $cat['link'].'/'.$row['link'];

			preg_match('/<img(\s|.*)src=[\'"](?P<src>.+?)[\'"].*>/i', $row['content'], $image);
			if ($image) $row['thumb'] = $image['src'];
			//else $row['thumb'] = MAIN_URL.'/data/img/'.rand(5,36).'.jpg';
			else $row['thumb'] = MAIN_URL.'/data/img/'.((int)$row['id']%32+4).'.jpg';
			$row['content'] = substr(strip_tags($row['content']), 0, 220);
			
			$ar[] = $row;
		}

		return $ar;
	}
	function getForum ($f) {
		$query = "SELECT id,title,link FROM forum WHERE id = ? LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $f);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row['link'] = $this->bLink.'/'.$row['link'];
		return $row;
	}
	function getLastPost ($id) {
		$query = "SELECT uid,created FROM topic_replies WHERE tid = ? ORDER BY created DESC LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row['author'] = $this->getUserInfo($row['uid']);

		if ($row) return $row;
		else return false;
	}
}
?>
