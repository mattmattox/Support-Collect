<?php
// 'node' object
class Node{

    // database connection and table name
    private $conn;
    private $table_name = "node";

    // object properties
	public $id;
  public $environmentid;
  public $clusterid;
  public $name;
  public $os_family;
  public $os_version;
  public $docker_version;
  public $role_etcd;
  public $role_control;
  public $role_worker;
	public $status;
  public $health;

	// constructor
    public function __construct($db){
        $this->conn = $db;
    }

	public function countSearch($keywords){

		$query = "SELECT COUNT(*) as total_rows
					FROM " . $this->table_name . "
					WHERE clusterid LIKE ? OR name LIKE ?";

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
				WHERE name LIKE ? OR clusterid LIKE ?
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
    $this->clusterid = $row['clusterid'];
    $this->name = $row['name'];
    $this->os_family = $row['os_family'];
    $this->os_version = $row['os_version'];
    $this->docker_version = $row['docker_version'];
    $this->created = $row['created'];
    $this->updated = $row['updated'];
    $this->status = $row['status'];
    $this->role_etcd = $row['role_etcd'];
    $this->role_control = $row['role_control'];
    $this->role_worker = $row['role_worker'];
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

    // update a node record
    public function update(){

    	$query = "UPDATE " . $this->table_name . "
    			SET
          environmentid = :environmentid,
          clusterid = :clusterid,
          name = :name,
          os_family = :os_family,
          os_version = :os_version,
          docker_version = :docker_version,
          status = :status,
          role_etcd = :role_etcd,
          role_control = :role_control,
          role_worker = :role_worker,
    			WHERE id = :id";

    	// prepare the query
    	$stmt = $this->conn->prepare($query);

    	// sanitize
      $this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
      $this->clusterid=htmlspecialchars(strip_tags($this->clusterid));
      $this->name=htmlspecialchars(strip_tags($this->name));
      $this->os_family=htmlspecialchars(strip_tags($this->os_family));
      $this->os_version=htmlspecialchars(strip_tags($this->os_version));
      $this->docker_version=htmlspecialchars(strip_tags($this->docker_version));
      $this->status=htmlspecialchars(strip_tags($this->status));
      $this->role_etcd=htmlspecialchars(strip_tags($this->role_etcd));
      $this->role_control=htmlspecialchars(strip_tags($this->role_control));
      $this->role_worker=htmlspecialchars(strip_tags($this->role_worker));

    	// bind the values from the form
      $stmt->bindParam(':environmentid', $this->environmentid);
      $stmt->bindParam(':clusterid', $this->clusterid);
      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':os_family', $this->os_family);
      $stmt->bindParam(':os_version', $this->os_version);
      $stmt->bindParam(':docker_version', $this->docker_version);
      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':role_etcd', $this->role_etcd);
      $stmt->bindParam(':role_control', $this->role_control);
      $stmt->bindParam(':role_worker', $this->role_worker);

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

    // check if given node exist in the database
	function nodeExists(){

		// query to check if node exists
		$query = "SELECT id, environmentid, name, status, health
				FROM " . $this->table_name . "
				WHERE environmentid = ? AND clusterid = ? AND name = ?
				LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
    $this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
    $this->clusterid=htmlspecialchars(strip_tags($this->clusterid));
    $this->name=htmlspecialchars(strip_tags($this->name));

		// bind given endpoint value
    $stmt->bindParam(1, $this->environmentid);
    $stmt->bindParam(2, $this->clusterid);
    $stmt->bindParam(3, $this->name);

		// execute the query
		$stmt->execute();

		// get number of rows
		$num = $stmt->rowCount();

		// if node exists, assign values to object properties for easy access and use for php sessions
		if($num>0){

			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// assign values to object properties
			$this->id = $row['id'];
      $this->environmentid = $row['environmentid'];
      $this->clusterid = $row['clusterid'];
      $this->name = $row['name'];
      $this->os_family = $row['os_family'];
      $this->os_version = $row['os_version'];
      $this->docker_version = $row['docker_version'];
      $this->status = $row['status'];
      $this->role_etcd = $row['role_etcd'];
			$this->role_control = $row['role_control'];
			$this->role_worker = $row['role_worker'];
      $this->health = $row['health'];

			// return true because endpoint exists in the database
			return true;
		}

		// return false if endpoint does not exist in the database
		return false;
	}

    // create new node record
    function create(){

    // insert query
    $query = "INSERT INTO " . $this->table_name . "
       SET
       environmentid = :environmentid,
       clusterid = :clusterid,
       name = :name,
       os_family = :os_family,
       os_version = :os_version,
       docker_version = :docker_version,
       status = :status,
       role_etcd = :role_etcd,
       role_control = :role_control,
       role_worker = :role_worker,
			 health = :health";

		// prepare the query
    $stmt = $this->conn->prepare($query);

		// sanitize
    $this->environmentid=htmlspecialchars(strip_tags($this->environmentid));
    $this->clusterid=htmlspecialchars(strip_tags($this->clusterid));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->os_family=htmlspecialchars(strip_tags($this->os_family));
    $this->os_version=htmlspecialchars(strip_tags($this->os_version));
    $this->docker_version=htmlspecialchars(strip_tags($this->docker_version));
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->role_etcd=htmlspecialchars(strip_tags($this->role_etcd));
    $this->role_control=htmlspecialchars(strip_tags($this->role_control));
    $this->role_worker=htmlspecialchars(strip_tags($this->role_worker));
    $this->health=htmlspecialchars(strip_tags($this->health));

		// bind the values
    $stmt->bindParam(':environmentid', $this->environmentid);
    $stmt->bindParam(':clusterid', $this->clusterid);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':os_family', $this->os_family);
    $stmt->bindParam(':os_version', $this->os_version);
    $stmt->bindParam(':docker_version', $this->docker_version);
    $stmt->bindParam(':status', $this->status);
    $stmt->bindParam(':role_etcd', $this->role_etcd);
    $stmt->bindParam(':role_control', $this->role_control);
    $stmt->bindParam(':role_worker', $this->role_worker);
    $stmt->bindParam(':health', $this->health);

    // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }

        return false;
    }
}
?>
