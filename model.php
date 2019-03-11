<?php

/*	Why make Model a database class, instead of ? 
		Abstracting away all database specific information into its own class. 
		The model can then deal with different databases at the same time.
*/

require('websiteDB.php');

class Model {
    private $webdb;

    public function __construct(){
        $this->webdb = new websiteDB();
    }

    public function validateUser($user_name, $password) {
    	return $this->webdb->validateUser($user_name, $password);
    }
}

?>