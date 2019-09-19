<?php
// 'environment' object
class Environment{

    // database connection and table name
    private $conn;
    private $table_name = "environment";

    // object properties
	public $id;
	public $name;
	public $endpoint;
	public $accesskey;
	public $secretkey;

	// constructor
    public function __construct($db){
        $this->conn = $db;
    }

	public function countSearch($keywords){

		$query = "SELECT COUNT(*) as total_rows
					FROM " . $this->table_name . "
					WHERE name LIKE ? OR endpoint LIKE ? OR accesskey LIKE ?";

		$stmt = $this->conn->prepare( $query );

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind variable values
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	function searchPaging($keywords, $from_record_num, $records_per_page){

		// select all query
		$query = "SELECT *
				FROM " . $this->table_name . "
				WHERE name LIKE ? OR endpoint LIKE ? OR accesskey LIKE ?
				ORDER BY created DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind variable values
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);
		$stmt->bindParam(4, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(5, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;

	}

	function readOne(){

		// query to read single record
		$query = "SELECT *
				FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id of product to be updated
		$stmt->bindParam(1, $this->id);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties
		$this->name = $row['name'];
		$this->endpoint = $row['endpoint'];
		$this->accesskey = $row['accesskey'];
		$this->secretkey = $row['secretkey'];
	}

	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " WHERE status='Active'";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function readPaging($from_record_num, $records_per_page){

		// select query
		$query = "SELECT *
				FROM " . $this->table_name . "
				WHERE status='Active'
				ORDER BY created DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind variable values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}

    // update a environment record
    public function update(){


    	// if no posted password, do not update the password
    	$query = "UPDATE " . $this->table_name . "
    			SET
    				name = :name,
    				endpoint = :endpoint,
					  accesskey = :accesskey,
					  secretkey = :secretkey
    			WHERE id = :id";

    	// prepare the query
    	$stmt = $this->conn->prepare($query);

    	// sanitize
    	$this->name=htmlspecialchars(strip_tags($this->name));
    	$this->endpoint=htmlspecialchars(strip_tags($this->endpoint));
		  $this->accesskey=htmlspecialchars(strip_tags($this->accesskey));
		  $this->secretkey=htmlspecialchars(strip_tags($this->secretkey));

    	// bind the values from the form
    	$stmt->bindParam(':name', $this->name);
    	$stmt->bindParam(':endpoint', $this->endpoint);
		  $stmt->bindParam(':accesskey', $this->accesskey);
		  $stmt->bindParam(':secretkey', $this->secretkey);

      // unique ID of record to be edited
    	$stmt->bindParam(':id', $this->id);

    	// execute the query
    	if($stmt->execute()){
    		return true;
    	}

		print_r($stmt->errorInfo());
    	return false;
    }

    // check if given endpoint exist in the database
	function endpointExists(){

		// query to check if endpoint exists
		$query = "SELECT id, name, endpoint, accesskey, secretkey
				FROM " . $this->table_name . "
				WHERE endpoint = ?
				LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->endpoint=htmlspecialchars(strip_tags($this->endpoint));

		// bind given endpoint value
		$stmt->bindParam(1, $this->endpoint);

		// execute the query
		$stmt->execute();

		// get number of rows
		$num = $stmt->rowCount();

		// if endpoint exists, assign values to object properties for easy access and use for php sessions
		if($num>0){

			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// assign values to object properties
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->endpoint = $row['endpoint'];
			$this->accesskey = $row['accesskey'];
      $this->secretkey = $row['secretkey'];

			// return true because endpoint exists in the database
			return true;
		}

		// return false if endpoint does not exist in the database
		return false;
	}

    // create new environment record
    function create(){

    // insert query
    $query = "INSERT INTO " . $this->table_name . "
       SET
			  name = :name,
			  endpoint = :endpoint,
			  accesskey = :accesskey,
			  secretkey = :secretkey";

		// prepare the query
    $stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->endpoint=htmlspecialchars(strip_tags($this->endpoint));
		$this->accesskey=htmlspecialchars(strip_tags($this->accesskey));
		$this->secretkey=htmlspecialchars(strip_tags($this->secretkey));

		// bind the values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':endpoint', $this->endpoint);
		$stmt->bindParam(':accesskey', $this->accesskey);
		$stmt->bindParam(':secretkey', $this->secretkey);

		// execute the query, also check if query was successful
    if($stmt->execute()){
      return true;
    }

      return false;
    }

}
?>
