<?php
class Course extends Config {

	// database connection and table name
//	private $conn;
	private $table_name = "courses";

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
					title = ?, code = ?, content = ?, cid = ?, uid = ?, 
					input = ?, output = ?, score = ?, time_limit = ?, memory_limit = ?, tests = ?, 
					color = ?, created = ?, modified = ?";

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

		if ($stmt->execute()) return true;
		else return false;
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
			if ($row) {
				$row['link'] = $this->pLink.'/'.$row['code'];
				$row['author'] = $this->getUserInfo($row['uid']);
			
				$this->cList[] = $row;
			}
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

		$this->id = $row['id'];
		$this->title = $row['title'];
		$this->code = $row['code'];
		$this->link = $this->pLink.'/'.$row['code'];
		$this->content = $row['content'];
		$this->cid = $row['cid'];
		$this->views = $row['views'];
		$this->uid = $row['uid'];
		
		if ($row) {
			$this->author = $row['author'] = $this->getUserInfo($this->uid);
			return $row;
		} else return false;
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
