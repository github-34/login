<?php

/*
     Session handling is separated.

*/
require('session.php');

class Controller {
    private $model;
    private $sess;
    private $userid;

    public function __construct($model) {
        $this->model = $model;
        $handler = new MySessionHandler($model);
        session_set_save_handler($handler, true);
        session_start();
        $handler->postVars();
        $this->sess = $handler;

    }

    /* 
        Session Interaction
    */
    // Are username and password values in POST?
    public function verifyPostLogin() {   
        return ($this->sess->getUsername() and $this->sess->getPassword()); //(isset($this->username) and isset($this->password) ) ? 1 : 0;
    }
    public function verifyPostLogout() {
        return $this->sess->getLogout(); //isset($this->logout);
    }
    public function logout() {
        return $this->sess->destroy($sess->getSessionID());
    }

    /* 
        Model Interaction 
    */

    public function verifyLogin() {
        return $this->model->validateUser($this->sess->getUsername(), $this->sess->getPassword()); //either valid user found or not.
    }
    public function getSess() {
        return $this->sess;
    }
}
?>