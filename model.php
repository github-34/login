<?php

/*	Why make Model a database class, instead of ? 
		Abstracting away all database specific information into its own class. 
		The model can then deal with different databases at the same time.
		Ensures that all sql to a particular database is contained in one class.
*/

//require('websiteDB.php');
require('database.php');

class websiteModel extends database {

    private $dbhost = "localhost";
    private $dbdriver = "mysql";
    private $dbuname = "root";
    private $dbpass = "";
    private $dbname = "website";

    function __construct() {
        parent::__construct($this->dbhost,$this->dbdriver, $this->dbuname, $this->dbpass, $this->dbname);
    }

    /* User Tables */

    // Return user_id if user found; false otherwise.
    function validateUser($user_name, $password) {
        $where = "name = ? AND password = ?";       // where clauses MUST be parameterized to prevent SQL Injection attacks
        $arr =  array($user_name, $password);
        $rows = $this->select("users", $where, $arr);
        return (sizeof($rows) === 1)? $rows[0]['userid'] : FALSE;
    }
    /* Session Tables */
    function readSession($session_id) {
        $where = " sessionid = ? "; 
        $arr = array ($session_id);
        $rows = $this->select("sessions", $where, $arr);
 //       echo "Read Session Array9999999999".$session_id;
        echo "----READSESSIONROWS: ".$session_id ;    
        print_r($rows);
        return $rows;
    }

    function deleteSession($session_id){ 
        $where = "sessionid = ?";
        $arr = array($session_id);
        return $this->delete("sessions", $where, $arr); // returns rowcount
    }
    
    function addSession($session_id, $data) {
        $where = "";
        echo "AAAAAAAAAAAAA".$session_id;
        $arr = array ("sessionid" => $session_id, "data" => $data);
        $rows = $this->insert("sessions", $arr );
        //$rows = $this->update("sessions", $data,  $arr );
        return $rows;
    }
/*
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