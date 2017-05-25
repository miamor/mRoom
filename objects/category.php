<?php
class Category extends Config {

	// database connection and table name
	private $table_name = "categories";

	// object properties
	public $id;
	public $title;

	public function __construct() {
		parent::__construct();
	}

	// used by select drop-down list
	function read () {
		//select all data
		$query = "SELECT
					id, title, link
				FROM
					" . $this->table_name . "
				ORDER BY
					title";	

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row['link'] = $this->cLink.'/'.$row['link'];
			$row['num'] = $this->getProbNum($row['id']);
			$this->catList[] = $row;
		}

		return $stmt;
	}
	function getProbNum ($id) {
		$query = "SELECT * FROM problems WHERE cid = ?";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $id);
		$stmt->execute();

		return $stmt->rowCount();
	}
		
	// used to read category name by its ID
	function readName(){
		
		$query = "SELECT title FROM " . $this->table_name . " WHERE id = ? limit 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->title = $row['title'];
	}


}
?>
