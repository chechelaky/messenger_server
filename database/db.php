<?php
class DB {
	private $pdo;

	private $listInsult=array(
		'chenapan',
		'goujat',
		'crapule'
	);

	public function DB($dsn, $username, $password, $options = null) {
		$this->pdo = new PDO($dsn, $username, $password, $options);
	}

	public function find($class, $table, $where, $whereArgs = array(), $order = null) {
		$sql = "SELECT * FROM $table WHERE $where";
		if ( $order != null ) {
			$sql .= " ORDER BY $order";
		}
		$sql .= ' LIMIT 1';
		$pdoStatement = $this->pdo->prepare($sql);
		foreach ($whereArgs as $value) {
			$value = htmlentities(addcslashes(mysql_real_escape_string($value), '%_'));
		}
		$pdoStatement->execute($whereArgs);
		return $pdoStatement->fetchObject($class);
	}

	public function search($class, $table, $where, $whereArgs = array(), $order = null) {
		$sql = "SELECT * FROM $table WHERE $where";
		if ( $order != null ) {
			$sql .= " ORDER BY $order";
		}
		$pdoStatement = $this->pdo->prepare($sql);
		$pdoStatement->execute($whereArgs);
		return $pdoStatement->fetchAll(PDO::FETCH_CLASS, $class);
	}

	public function insert($model, $table) {
		if ( in_array($model->toDB()['message'], $this->listInsult)){
			return false;
		} 
		$fields = '';
		$values = '';
		$whereArgs = array();
		foreach ( $model->toDB() as $name => $value ) {
			if($fields != '')
			{
				$fields .= ', ';
				$values .= ', ';
			}

			$fields .= $name;
			$values .= ":$name";

			$whereArgs[":$name"] = htmlentities($value);
		}
		$sql = "INSERT INTO $table ($fields) VALUES ($values)";
		$pdoStatement = $this->pdo->prepare($sql);

		$result = $pdoStatement->execute($whereArgs);
		if ( !$result ) {
			return false;
		}
		return $this->pdo->lastInsertId();
	}

	public function insertContact($model, $table) {
		$fields = '';
		$values = '';
		$whereArgs = array();
		foreach ( $model->toDB() as $name => $value ) {
			if($fields != '')
			{
				$fields .= ', ';
				$values .= ', ';
			}

			$fields .= $name;
			$values .= ":$name";

			$whereArgs[":$name"] = htmlentities($value);
		}
		$sql = "INSERT INTO $table ($fields) VALUES ($values)";
		$pdoStatement = $this->pdo->prepare($sql);

		$result = $pdoStatement->execute($whereArgs);
		if ( !$result ) {
			return false;
		}
		return $this->pdo->lastInsertId();
	}

	public function update($model, $table, $where, $whereArgs = array()) {
		$set = '';
		foreach ( $model as $name => $value ) {
			if ( $set != '' ) {
				$set .= ', ';
			}
			$set .= "$name = :$name";
			$whereArgs[":$name"] = $value;
		}
		$sql = "UPDATE $table SET $set WHERE $where";
		$pdoStatement = $this->pdo->prepare($sql);
		return $pdoStatement->execute($whereArgs);
	}

	public function delete($table, $where, $whereArgs = array()) {
		$sql = "DELETE FROM $table WHERE $where";
		$pdoStatement = $this->pdo->prepare($sql);
		foreach ($whereArgs as $value) {
			$value = $value;
		}
		return $pdoStatement->execute($whereArgs);
	}
}