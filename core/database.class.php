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

	public function error(){
		return $this->connection()->error; 
	}

	public function query($query)
	{
		//change the number of affected rows after a query
		$result = $this->connection()->query($query);
		$num_rows = $this->connection()->affected_rows;
		return $num_rows;
	}

	public function fetch($query){
		return mysqli_fetch_assoc($this->query($query));
	}
}