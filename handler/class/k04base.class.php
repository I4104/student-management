<?php
	class k04base {

		private $conn;
		private $table;

		function __construct($conn) {
			$this->conn = $conn;
		}

		function setTable($table) {
			$this->table = $table;	
		}

		function get($find = array(), $limit = array(), $order = "id", $filter = "ASC") {
			$sql = "SELECT * FROM ". $this->table. " ";
			if (count($find) > 0) {
				$sql .= "WHERE ";
				foreach ($find as $key => $value) {
					$sql .= $key. " = '". $value ."' AND";
				}
				$sql = rtrim($sql, " AND");
			}
			$sql .= " ORDER BY ". $order . " ". $filter;
			if (count($limit) > 0) {
				$sql .= " LIMIT ". $limit["start"] .", ". $limit["limit"] ;
			}
            //return $sql;
			return $this->conn->query($sql);
		}

		function insert($insert = array()) {
			$sql = "INSERT INTO ". $this->table;
			if (count($insert) > 0) {
				$name = "";
				$add = "";
				foreach ($insert as $key => $value) {
					$name .= $key.", ";
					$add .= "'". $value ."', ";
				}
				$name = rtrim($name, ", ");
				$add = rtrim($add, ", ");
				$sql .= "(".$name.") VALUES (". $add .")";
				$message = $this->conn->query($sql) ? "success" : "error";
			} else {
				$message = "waring";
			}
			return $message;
		}

		function remove($find = array()) {
			$sql = "DELETE FROM ". $this->table. " ";
			if (count($find) > 0) {
				$sql .= "WHERE ";
				foreach ($find as $key => $value) {
					$sql .= $key. "'". $value ."' AND";
				}
				$sql = rtrim($sql, " AND");
			}
			return $this->conn->query($sql);
		}

		function update($update = array(), $find = array()) {
			$sql = "UPDATE ". $this->table. " SET ";
			if (count($update) > 0) {
				foreach ($update as $key => $value) {
					$sql .= $key. " = '". $value ."', ";
				}
				$sql = rtrim($sql, ", ");
				if (count($find) > 0) {
					$sql .= " WHERE ";
					foreach ($find as $key => $value) {
						$sql .= $key. "'". $value ."' AND";
					}
					$sql = rtrim($sql, " AND");
				}
				$message = $this->conn->query($sql) ? "success" : "error";
			} else {
				$message = "waring";
			}
			return $message;
		}

	}

?>