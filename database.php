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

class database
{
    private $db = "";

	public function __construct($db_host, $db_driver, $db_uname, $db_upass, $db_name)
    {
		try
        {
            switch($db_driver)
            {
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
		} 
        catch(PDOException $e)
        {
            echo "Database connection unsuccessful:".$e->getMessage(); 
            exit(1);
        }
	}	

    public function run($sql, $bind=array())
    {
        $sql = trim($sql);
        try
        {
            if (DEBUG) View::print_rr("---DB: sql: ", $sql);

            $result = $this->db->prepare($sql);         // PHP escape characters

            if (DEBUG) {View::print_rr("---DB: prepare: ", $result); View::print_rr("---DB: bind: ", $bind);}
            
//session_destroy(); //$this->sess->destroy($sess->getSessionID());
            $result->execute($bind);
            return $result;
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            exit(1);
        }
    }

    public function insert($table, $data)
    {            
        $fields = $this->filter($table, $data);
        $sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
        $bind = array();
        foreach($fields as $field)
            $bind[":$field"] = $data[$field];
        $result = $this->run($sql, $bind);
        return $this->db->lastInsertId();
    }

    public function select($table, $where="", $bind=array(), $fields="*")
    {
        $sql = "SELECT " . $fields . " FROM " . $table;
        (empty($where)) ? $sql .= ";" : $sql .= " WHERE " . $where . ";" ;
        $result = $this->run($sql, $bind);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $rows = array();
        while($row = $result->fetch())
            $rows[] = $row;
        return $rows;
    }

    public function selectJoin($sql,$bind)
    {
        $result = $this->run($sql, $bind);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $rows = array();
        while($row = $result->fetch())
            $rows[] = $row;
      
        if (DEBUG) { View::print_rr("DB: SQL: ", $sql); View::print_rr("DB: Bind",$bind); View::print_rr("DB: rows:", $rows);}

        return $rows;
    }

    public function update($table, $data, $where, $bind=array())
    {
        $fields = $this->filter($table, $data);
        $fieldSize = sizeof($fields);
        $sql = "UPDATE " . $table . " SET ";
        for($f = 0; $f < $fieldSize; ++$f) {
            if($f > 0)
                $sql .= ", ";
            $sql .= $fields[$f] . " = :update_" . $fields[$f]; 
        }
        $sql .= " WHERE " . $where . ";";

        if (DEBUG) { View::print_rr("DB: SQL: ", $sql); View::print_rr("DB: data",$data); View::print_rr("DB: Bind",$bind); View::print_rr("DB: fields:", $fields); }

        foreach($fields as $field)
            $bind[":update_$field"] = $data[$field];

        if (DEBUG) { View::print_rr("DB: sql: ", $sql); View::print_rr("DB: bind: ", $bind);}
        
        $result = $this->run($sql, $bind);
        return $result->rowCount();
    }
    
    public function delete($table, $where, $bind="") {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        $result = $this->run($sql, $bind);
        return $result->rowCount();
    }

    private function filter($table, $data)
    {
        $driver = 'mysql';
        if($driver == 'mysql')
        {
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        }
        else
        {    
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }   
        
        if(false !== ($list = $this->run($sql)))
        {
            $fields = array();
            foreach($list as $record)
                $fields[] = $record[$key];
            return array_values(array_intersect($fields, array_keys($data)));
        }
        return array();
    }

//    Insert: Insert new record
//  Update: Update existing record
//  Replace: works exactly like INSERT, except that if an old row in the table has the same value as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted

}

?>