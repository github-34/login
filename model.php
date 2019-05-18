<?php

/*	Why make Model a database class, instead of ? 
		Abstracting away all database specific information into its own class. 
		The model can then deal with different databases at the same time.
		Ensures that all sql to a particular database is contained in one class.
*/

require('database.php');

class Model extends database {

    private $dbhost = "localhost";
    private $dbdriver = "mysql";
    private $dbuname = "root";
    private $dbpass = "";
    private $dbname = "website";

    function __construct()
    {
        parent::__construct($this->dbhost,$this->dbdriver, $this->dbuname, $this->dbpass, $this->dbname);
    }

    // Return user_id if user found; false otherwise.
    function validateUser($user_name, $password)
    {
        $where = "name = ? AND password = ?";       // where clauses MUST be parameterized to prevent SQL Injection attacks
        $arr =  array($user_name, $password);
        $rows = $this->select("users", $where, $arr);

        return (sizeof($rows) === 1)? $rows[0]['userid'] : FALSE;
    }

    function readSession($session_id)
    {
        $where = " sessionid = ? "; 
        $arr = array ($session_id);
        $rows = $this->select("sessions", $where, $arr);
        
        if (DEBUG) View::print_rr("-------Model: ReadSession: rows", $rows);  

        return $rows;
    }
    
    function deleteSession($session_id)
    { 
        $where = " sessionid = ?";
        $arr = array($session_id);

        if (DEBUG) View::print_rr("-------Model: Deleting Session: sessionid", $session_id);  
    
        return $this->delete("sessions", $where, $arr); // returns rowcount
    }

    function addSession($session_id, $data)
    {    
        $where = " sessionid = ? "; 
        $arr = array ($session_id);
        $rows = $this->select("sessions", $where, $arr);

        if (DEBUG) View::print_rr("-------Model: Add Session: select", $rows);  
        if (DEBUG) View::print_rr("-------Model: Add Session: data", $data);  
        if (sizeof($rows) > 0)
        {
            //update
            $id = $rows[0]['id'];
            $where = " id = :id ";
            $arr = array(":id" => $id);
            $datasql = array ("sessionid" => $session_id, "data" => $data);

            if (DEBUG) View::print_rr("-------Model: Add Session (updating): rows", $datasql);  
            if (DEBUG) View::print_rr("-------Model: Add Session (updating): where", $where);  
            if (DEBUG) View::print_rr("-------Model: Add Session (updating): arr", $arr);  

            $rows = $this->update("sessions", $datasql, $where, $arr );   

            if (DEBUG) View::print_rr("-------Model: Add Session (updating): rows", $rows);  
        }
        else
        { 
            if (DEBUG) View::print_rr("-------Model: Add Session (inserting): rows", $data);  
            $arr = array ("sessionid" => $session_id, "data" => $data);
            $rows = $this->insert("sessions", $arr );
        }

        return $rows;
    }
/*
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

//  $this->model->insertSession($session_id, $data);
        $storeDataQuery = "REPLACE INTO `mssession` (`id`, `data`, `last_access`) VALUES(?, ?, ?)";
        $storeData = $this->dbConnection->prepare($storeDataQuery);

        if ($storeData !== false) {
          $storeData->bind_param('sss', $sessionId, $sessionData,
                                $this->accessTime);
          if ($storeData->execute() !== false) {
            if ($storeData->affected_rows > 0) {
              return true;
            }
          } else {
            if (self::SHOW_ERROR) {
              echo $storeData->error;
            }
          }
          $storeData->close();
        } else {
          if (self::SHOW_ERROR) {
            echo $storeData->error;
          }
        }*/

    }

?>