<?php
class Team extends Config {

	// database connection and table name
	private $table_name = "team";

	// object properties
	public $id;
	public $title;

	public function __construct() {
		parent::__construct();
/*		$u = $this->u;
		$query = "SELECT id FROM " . $this->table_name . " WHERE users LIKE '%,{$u},%' OR users LIKE '{$u},%' OR users LIKE '%,{$u}' OR users = '{$u}' ";	
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
*/	}

/*	function selectTeam ($tid) {
		$u = $this->u;
		$query = "SELECT id FROM " . $this->table_name . " WHERE users LIKE '%,{$tid}' ";	
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $tid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row['id']) {
			$this->id = $row['id'];
			return true;
		}
		return false;
	}
*/
	function readAll ($limit = null) {
		if ($limit) $lim = "LIMIT 0,{$limit}";
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				ORDER BY
					rank ASC
				{$lim}";	

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$teamList = array();
		$prevScore = $prevRank = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->tmLink.'/'.$row['id'];
			$row['usersID'] = explode(',', $row['users']);
/*			foreach ($row['usersID'] as $rU) {
				echo $rU;
				$row['users'][] = $this->getUserInfo($rU);
			}
*/			$row['usersNum'] = count($row['usersID']);
			
			if ($prevRank == 0) $row['rank'] = 1;
			else if ($row['score'] == $prevScore) $row['rank'] = $prevRank;
			else $row['rank'] = $prevRank + 1;
			
			$teamList[] = $row;
		}

		return $teamList;
	}

	function readAllMy ($u = null) {
		if (!$u) $u = $this->u;
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					users LIKE '%,{$u},%' OR users LIKE '{$u},%' OR users LIKE '%,{$u}' OR users = '{$u}' 
				ORDER BY
					rank ASC";	
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$teamList = array();
		$prevScore = $prevRank = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->tmLink.'/'.$row['id'];
			$row['usersID'] = explode(',', $row['users']);
			$row['members'] = array();
			foreach ($row['usersID'] as $rU) {
				$row['members'][] = $this->sGetUserInfo($rU);
			}
			$row['usersNum'] = count($row['usersID']);
			
			if ($prevRank == 0) $row['rank'] = 1;
			else if ($row['score'] == $prevScore) $row['rank'] = $prevRank;
			else $row['rank'] = $prevRank + 1;
			
			$prevScore = $row['score'];
			$prevRank = $row['rank'];

			$teamList[] = $row;
		}

		return $teamList;
	}
	
	function sReadOne ($uid = null) {
		$query = "SELECT id,title,users,rank FROM " . $this->table_name . " WHERE id = ? limit 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->title = $row['title'];
		$this->link = $this->tmLink.'/'.$row['id'];
		return $row;
	}
	
	function readOne ($withC = false, $u = null) {
		$cond = '';
		if (!$u) $u = $this->u;
		if ($withC == true) {
			$cond = "OR (users = '{$u}' OR users LIKE '{$u},%' OR users LIKE '%,{$u}' OR users LIKE '%,{$u},%')";
			//$cond = "OR INSTR('users', '{$u}') > 0'";
		}
		
		$query = "SELECT * FROM " . $this->table_name . " WHERE id = ? {$cond} limit 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->title = $row['title'];
		$this->id = $row['id'];

		$row['link'] = $this->tmLink.'/'.$row['id'];
		$row['usersID'] = explode(',', $row['users']);
		$row['members'] = array();
		foreach ($row['usersID'] as $rU) {
			$row['members'][] = $this->sGetUserInfo($rU);
		}
		$row['usersNum'] = count($row['usersID']);

		return $row;
	}


}
?>
