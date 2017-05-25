<?php
class User extends Config {

	// database connection and table name
	private $table_name = "members";

	// object properties
	public $id;
	public $title;

	public function __construct() {
		parent::__construct();
	}

	function readAll ($limit = '') {
		if ($limit) $lim = "LIMIT 0,{$limit}";
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				ORDER BY
					rank ASC
				{$lim}";	

		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$prevScore = $prevRank = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->uLink.'/'.$row['username'];
			$row['name'] = ($row['last_name']) ? ($row['last_name'].' '.$row['first_name']) : $row['first_name'];
//			$row['name'] = ($row['last_name']) ? ($row['first_name'].' '.$row['last_name']) : $row['first_name'];
			$row['score'] = round($row['score'], 2);
		/*	Must update rankings for users, to show in user profile page	
			if ($prevRank == 0) $row['rank'] = 1;
			else if ($row['score'] == $prevScore) $row['rank'] = $prevRank;
			else $row['rank'] = $prevRank + 1;
		*/
			
			$this->uList[] = $row;
		}

		return $this->uList;
	}

	function readOne ($withC = false) {
		$cond = '';
		
		$query = "SELECT * FROM " . $this->table_name . " WHERE id = ? OR username = ? limit 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$row['link'] = $this->uLink.'/'.$row['username'];
		$row['name'] = ($row['last_name']) ? ($row['last_name'].' '.$row['first_name']) : $row['first_name'];

		$this->username = $row['username'];
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->avatar = $row['avatar'];
		$this->score = $row['score'];
		$this->rank = $row['rank'];
		$this->AC = $row['AC'];
		$this->WA = $row['WA'];
		$this->RTE = $row['RTE'];
		$this->DQ = $row['DQ'];
		$this->link= $row['link'];
		//$row['submissions'] = $this->countSubmissions($this->id);
		$this->submissions = $row['submissions'];

		return $row;
	}

	public function countSubmissions ($u) {
		if ($u) $cond = "WHERE uid = {$u}";
		
		$query = "SELECT id FROM submissions {$cond}";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}

	function updateRankings () {
		$query = "SELECT rank,score,id FROM " . $this->table_name." ORDER BY score DESC";

		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$k = 0;
		$prevScore = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if ($prevScore != $row['score']) $k++;
			$prevScore = $row['score'];
			$updateRank = $this->editUserData(array('rank' => $k), $row['id']);
		}

		if ($updateRank) return true;
	}
	
	function editUserData ($valueAr, $u) {
		if (!$u) $u = $this->id;
		
		$condAr = array();
		foreach ($valueAr as $vK => $oneField) 
			$condAr[] = "{$vK} = {$oneField}";
		$cond = implode(', ', $condAr);
		
		$query = "UPDATE
					" . $this->table_name . "
				SET
					{$cond}
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// bind parameters
/*		foreach ($valueAr as $vK => $oneVal) {
			$stmt->bindParam(':'.$vk, $oneVal);
		}
*/		$stmt->bindParam(':id', $u);

		// execute the query
		if ($stmt->execute()) return true; 
		else return false;
	}
}
?>
