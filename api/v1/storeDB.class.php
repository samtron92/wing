<?php
class storeDB {
	private $conn;
	public function __construct() {
		$servername = "localhost";
		$username = "root";
		$password = "root";
		try {
			$this->conn = new PDO("mysql:host=$servername;dbname=store", $username, $password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			die;
		}	
	}

	public function selectById($id) {
		try {
			$sql = "SELECT * FROM items WHERE id = :id";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}

	public function selectByName($name) {
		try {
			$sql = "SELECT * FROM items WHERE name = :name";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':name', $name);
			$stmt->execute();               
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}

	public function selectAll() {
		try {
			$sql = "SELECT * FROM items";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}

	public function insert($data) {
		try {
			$sql = "INSERT INTO items (name, quantity, price, description) VALUES (:name, :quantity, :price, :desc)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':name', $data['name']);
			$stmt->bindParam(':quantity', $data['quantity']);
			$stmt->bindParam(':price', $data['price']);
			$stmt->bindParam(':desc', $data['desc']);
			$stmt->execute();
			$last_id = $this->conn->lastInsertId();
			return "New record created successfully. Last inserted ID is: " . $last_id;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}

	public function update($id, $data) {
		try {
			$sql = "UPDATE items SET name = :name, quantity = :quantity, price = :price, description = :desc WHERE id = :id";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':name', $data['name']);
			$stmt->bindParam(':quantity', $data['quantity']);
			$stmt->bindParam(':price', $data['price']);
			$stmt->bindParam(':desc', $data['desc']);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			return "Record updated successfully. Last updated ID is: " . $id;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}

	public function delete($id) {
		try {
			$sql = "DELETE FROM items where id = :id";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			return "Record deleted successfully. Last deleted ID is: " . $id;
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die;
		}
	}
	
	public function selectUser($password) {
		try {
                        $sql = "SELECT username FROM authenticate WHERE password = :pass";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bindParam(':pass', $password);
                        $stmt->execute();
                        $result = $stmt->fetchColumn();
                        return $result;
                }
                catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                        die;
                }
	}
}
?>
