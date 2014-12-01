<?php 
if(!defined('VALID_SITE')) exit('No direct access! ');
class Database
{
	public function __construct($host, $user, $pass, $db)
	{
		$this->connection = new mysqli($host, $user, $pass, $db); 
		if ($this->connection->connect_errno) {
			echo "NOTE: Your MySQL connection is currently not working. " 
				. $this->connection->connect_errno;
		}
	}
	
	public function connection()
	{
		return $this->connection; 
	}

	public function error()
	{
		return $this->connection()->error; 
	}

	public function query($query)
	{
		return mysqli_query($this->connection(), $query);
	}

	public function fetch($res) 
	{
		if (is_string($res)) {
			$res = $this->query($res);
		}
		return mysqli_fetch_assoc($res);
	}

	public function fetchMany($query) 
	{
		$res = $this->query($query);
		while ($row = $this->fetch($res)) {
			$arr[] = $row;
		}
		return $arr;
	}

	public function numOfRows($query){
		// the number of affected rows after a query
		$result = $this->connection()->query($query);
		$num_rows = $this->connection()->affected_rows;
		return $num_rows;
	}
}