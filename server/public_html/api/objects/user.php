<?php
// 'user' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
	public $id;
	public $firstname;
	public $lastname;
	public $email;
	public $access_level;
	public $password;

	// constructor
    public function __construct($db){
        $this->conn = $db;
    }

	public function countSearch($keywords){

		$query = "SELECT COUNT(*) as total_rows 
					FROM " . $this->table_name . " 
					WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?";

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
				WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?
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
		$this->firstname = $row['firstname'];
		$this->lastname = $row['lastname'];
		$this->email = $row['email'];
		$this->access_level = $row['access_level'];
	}

	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " WHERE access_level='Customer'";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function readPaging($from_record_num, $records_per_page){

		// select query
		$query = "SELECT * 
				FROM " . $this->table_name . " 
				WHERE access_level='Customer'
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

    // update a user record
    public function update(){

        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";

    	// if no posted password, do not update the password
    	$query = "UPDATE " . $this->table_name . "
    			SET
    				firstname = :firstname,
    				lastname = :lastname,
					email = :email,
					access_level = :access_level
    				{$password_set}
    			WHERE id = :id";

    	// prepare the query
    	$stmt = $this->conn->prepare($query);

    	// sanitize
    	$this->firstname=htmlspecialchars(strip_tags($this->firstname));
    	$this->lastname=htmlspecialchars(strip_tags($this->lastname));
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->access_level=htmlspecialchars(strip_tags($this->access_level));

    	// bind the values from the form
    	$stmt->bindParam(':firstname', $this->firstname);
    	$stmt->bindParam(':lastname', $this->lastname);
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':access_level', $this->access_level);

    	// hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
    		$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    		$stmt->bindParam(':password', $password_hash);
        }

    	// unique ID of record to be edited
    	$stmt->bindParam(':id', $this->id);

    	// execute the query
    	if($stmt->execute()){
    		return true;
    	}

		print_r($stmt->errorInfo());
    	return false;
    }

    // check if given email exist in the database
	function emailExists(){

		// query to check if email exists
		$query = "SELECT id, firstname, lastname, access_level, password
				FROM " . $this->table_name . "
				WHERE email = ?
				LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->email=htmlspecialchars(strip_tags($this->email));

		// bind given email value
		$stmt->bindParam(1, $this->email);

		// execute the query
		$stmt->execute();

		// get number of rows
		$num = $stmt->rowCount();

		// if email exists, assign values to object properties for easy access and use for php sessions
		if($num>0){

			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// assign values to object properties
			$this->id = $row['id'];
			$this->firstname = $row['firstname'];
			$this->lastname = $row['lastname'];
			$this->access_level = $row['access_level'];
            $this->password = $row['password'];

			// return true because email exists in the database
			return true;
		}

		// return false if email does not exist in the database
		return false;
	}

    // create new user record
    function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
					firstname = :firstname,
					lastname = :lastname,
					email = :email,
					access_level = :access_level,
					password = :password";

		// prepare the query
        $stmt = $this->conn->prepare($query);

		// sanitize
		$this->firstname=htmlspecialchars(strip_tags($this->firstname));
		$this->lastname=htmlspecialchars(strip_tags($this->lastname));
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->access_level=htmlspecialchars(strip_tags($this->access_level));
		$this->password=htmlspecialchars(strip_tags($this->password));

		// bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':access_level', $this->access_level);

		// hash the password before saving to database
		$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
		$stmt->bindParam(':password', $password_hash);

		// execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }

        return false;
    }

}
?>
