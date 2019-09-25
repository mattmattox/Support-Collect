<?php
// 'cluster' object
class Cluster{

    // database connection and table name
    private $conn;
    private $table_name = "cluster";

    // object properties
	public $id;
	public $environmentid;
	public $name;
	public $status;
  public $type;
  public $health;

	// constructor
    public function __construct($db){
        $this->conn = $db;
    }

	public function countSearch($keywords){

		$query = "SELECT COUNT(*) as total_rows
					FROM " . $this->table_name . "
					WHERE environmentid LIKE ? OR name LIKE ?";

		$stmt = $this->conn->prepare( $query );

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind variable values
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	function searchPaging($keywords, $from_record_num, $records_per_page){

		// select all query
		$query = "SELECT *
				FROM " . $this->table_name . "
				WHERE name LIKE ? OR environmentid LIKE ?
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
		$stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

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
		$this->environmentid = $row['environmentid'];
		$this->name = $row['name'];
		$this->status = $row['status'];
		$this->type = $row['type'];
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
				ORDER BY created ASC
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

    // update a cluster record
    public function update(){

    	$query = "UPDATE " . $this->table_name . "
    			SET
    				environmentid = :environmentid,
    				name = :name,
					  status = :status,
					  type = :type
    			WHERE id = :id";

    	// prepare the query
    	$stmt = $this->conn->prepare($query);

    	// sanitize
    	$this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
    	$this->name=htmlspecialchars(strip_tags($this->name));
		  $this->status=htmlspecialchars(strip_tags($this->status));
		  $this->type=htmlspecialchars(strip_tags($this->type));

    	// bind the values from the form
    	$stmt->bindParam(':environmentid', $this->environmentid);
    	$stmt->bindParam(':name', $this->name);
		  $stmt->bindParam(':status', $this->status);
		  $stmt->bindParam(':type', $this->type);

      // unique ID of record to be edited
    	$stmt->bindParam(':id', $this->id);

    	// execute the query
    	if($stmt->execute()){
    		return true;
    	}

		print_r($stmt->errorInfo());
    	return false;
    }

    public function healthcheckupdate()
    {
      $query = "UPDATE " . $this->table_name . "
          SET
            health = :health,
            updated = now()
          WHERE id = :id";

      // prepare the query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->health=htmlspecialchars(strip_tags($this->health));
      $this->id=htmlspecialchars(strip_tags($this->id));

      // bind the values from the form
      $stmt->bindParam(':health', $this->health);

      // unique ID of record to be edited
      $stmt->bindParam(':id', $this->id);

      // execute the query
      if($stmt->execute()){
        return true;
      }

      print_r($stmt->errorInfo());
      return false;
    }

    // check if given cluster exist in the database
	function clusterExists(){

		// query to check if cluster exists
		$query = "SELECT id, environmentid, name, status, type, health
				FROM " . $this->table_name . "
				WHERE environmentid = ? OR name = ?
				LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
    $this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
    $this->name=htmlspecialchars(strip_tags($this->name));

		// bind given endpoint value
    $stmt->bindParam(1, $this->environmentid);
    $stmt->bindParam(2, $this->name);

		// execute the query
		$stmt->execute();

		// get number of rows
		$num = $stmt->rowCount();

		// if cluster exists, assign values to object properties for easy access and use for php sessions
		if($num>0){

			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// assign values to object properties
			$this->id = $row['id'];
			$this->environmentid = $row['environmentid'];
			$this->name = $row['name'];
			$this->status = $row['status'];
      $this->type = $row['type'];
      $this->health = $row['health'];

			// return true because endpoint exists in the database
			return true;
		}

		// return false if endpoint does not exist in the database
		return false;
	}

    // create new cluster record
    function create(){

    // insert query
    $query = "INSERT INTO " . $this->table_name . "
       SET
			  environmentid = :environmentid,
			  name = :name,
        status = :status,
        type = :type,
			  health = :health";

		// prepare the query
    $stmt = $this->conn->prepare($query);

		// sanitize
		$this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->status=htmlspecialchars(strip_tags($this->status));
    $this->type=htmlspecialchars(strip_tags($this->type));
    $this->health=htmlspecialchars(strip_tags($this->health));

		// bind the values
    $stmt->bindParam(':environmentid', $this->environmentid);
    $stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':status', $this->status);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':health', $this->health);

    // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }

        return false;
    }
}
?>
