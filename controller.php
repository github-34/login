<?php

/*
     Session handling is separated.

*/

class Controller {
    private $model;
    
    private $username;
    private $password;
    private $logout;

    public function __construct($model) {
        $this->model = $model;
 
        session_start();

        if ( isset($_POST['uname']) and isset($_POST['passwd'])) {
             $this->username = $_POST['uname'];
             $this->password = $_POST['passwd'];
        }
        if ( isset($_POST['logout']) ) {
            $this->logout = $_POST['logout'];
        }
    }

    // Are username and password values in POST. return True if both are; false otherwise
    public function verifyPostLogin() {   
        return (isset($this->username) and isset($this->password) ) ? 1 : 0;
    }
    public function verifyPostLogout() {
        return isset($this->logout);
    }

    public function logout() {
        unset($_SESSION['uname']);
        unset($_SESSION['passwd']);
        unset($_SESSION['logout']);
        unset($_SESSION);
        session_destroy();
    }

    /* 
        Model Interaction 
    */

    public function verifyLogin() {
        return $this->model->validateUser($this->username, $this->password); //either valid user found or not.
    }
}
?>