<?php

/*
     Session handling is separated.

*/
require('session.php');

class Controller 
{
    private $model;
    private $view;


    private $sess;
    private $userid;

    public function __construct($view, $model)
    {
        $this->model = $model;
        $this->view = $view;

        $handler = new MySessionHandler($model);
        session_set_save_handler($handler, true);
        session_start();

        $this->sess = $handler;
        $handler->outputSession();
    }

    public function loginPage()
    { 
        $_SESSION['temp'] = 'somedata123';

        if ($this->verifyPostLogin() )  {   //login attempt?
            if ($this->verifyLogin() )      // login success
                return $this->view->mainPage();
            else                                        // login fail: incorrect password
                return $this->view->loginPage(1);
           }
        elseif ($this->verifyPostLogout() ) {  //logout attempt from mainpage?
            $this->logout();
            return $this->view->loginPage(2);
        }
        else                                             // regular page; no login attempt or logout
            return $this->view->loginPage(0);
    }

    /* 
        Session Interaction
    */
    // Are username and password values in POST?
    public function verifyPostLogin()
    {   
        return ($this->sess->getUsername() or $this->sess->getPassword()); //(isset($this->username) and isset($this->password) ) ? 1 : 0;
    }
    
    public function verifyPostLogout() 
    {
        return $this->sess->getLogout(); //isset($this->logout);
    }
    
    public function logout()
    {
        $this->sess->destroy(session_id()); 
        return true;
    }

    public function verifyLogin() 
    {
        $id = $this->model->validateUser($this->sess->getUsername(), $this->sess->getPassword());
        if ($id != 0)
            $this->userid = $id;
        return $id;
    }

    public function getSess() 
    {
        return $this->sess;
    }
   
}
?>