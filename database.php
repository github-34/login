<?php

/* Database Wrapper Class 

    Designed around prevent all sql injection attacks.
        E.g. 
        SQL injections attacks are not prevented by "" prepare () functions.

    PDO 
    Parameratized Queries       - SQL injection attacks

    Source: https://phpdelusions.net/sql_injection
            https://dibiphp.com/
*/

class database {
    private $db = "";

	public function __construct($db_host, $db_driver, $db_uname, $db_upass, $db_name) {				// Equivalently use class name: public function database ($uname, $upass, $dbname, $dbdriver) {	
		try {
            switch($db_driver) {
                case "mysql":
                    $connstr = "mysql:host={$db_host};dbname={$db_name}";
                    break;
                default:
                    echo "The selected database driver, {$db_driver}, is not supported. ";
                    exit(1);
            }
			$this->db = new PDO($connstr, $db_uname, $db_upass);   
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);         // Disable the emulation of prepared statementsm: MySQL database real prepared statements are not used by default. makes sure the statement and the values aren't parsed by PHP before sending it to the MySQL server (giving a possible attacker no chance to inject malicious SQL).
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // The script will not stop with a Fatal Error and errors can be caught

		} catch(PDOException $e) {
            echo "Connection to database was unsuccessful:".$e->getMessage(); 
            exit(1);
        }
	}	
    public function run($sql, $bind=array()) {
        $sql = trim($sql);
        try {
            $result = $this->db->prepare($sql);         //
            $result->execute($bind);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit(1);
        }
    }
    public function insert($table, $data) {            
        $fields = $this->filter($table, $data);
        $sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
        $bind = array();
        foreach($fields as $field)
            $bind[":$field"] = $data[$field];
        $result = $this->run($sql, $bind);
        return $this->db->lastInsertId();
    }
    public function select($table, $where="", $bind=array(), $fields="*") {
        $sql = "SELECT " . $fields . " FROM " . $table;
        if(!empty($where))
            $sql .= " WHERE " . $where;
        $sql .= ";";
        $result = $this->run($sql, $bind);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $rows = array();
        while($row = $result->fetch()) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function update($table, $data, $where, $bind=array()) {
        $fields = $this->filter($table, $data);
        $fieldSize = sizeof($fields);
        $sql = "UPDATE " . $table . " SET ";
        for($f = 0; $f < $fieldSize; ++$f) {
            if($f > 0)
                $sql .= ", ";
            $sql .= $fields[$f] . " = :update_" . $fields[$f]; 
        }
        $sql .= " WHERE " . $where . ";";
        foreach($fields as $field)
            $bind[":update_$field"] = $data[$field];
        
        $result = $this->run($sql, $bind);
        return $result->rowCount();
    }
    public function delete($table, $where, $bind="") {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        $result = $this->run($sql, $bind);
        return $result->rowCount();
    }	
}

?>