<?php

require('database.php');

class websiteDB extends database { 

	private $dbhost = "localhost";
	private $dbdriver = "mysql";
	private $dbuname = "root";
	private $dbpass = "";
	private $dbname = "website";

	function __construct() {
		parent::__construct($this->dbhost,$this->dbdriver, $this->dbuname, $this->dbpass, $this->dbname);
	}

	function validateUser($user_name, $password) {
		$where = "name = ? AND password = ?";		// where clauses MUST be parameterized to prevent SQL Injection attacks
		$arr =  array($user_name, $password);

        $rows = $this->select("users", $where, $arr);
		return (sizeof($rows) === 1);
    }
}


?>