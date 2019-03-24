<?php


class View {
    private $model;
    private $controller;
 
    public function __construct($controller, $model) {
        $this->controller = $controller;
        $this->model = $model;
    }
	
    public function outputLogin(){ 
        if ($this->controller->verifyPostLogin() )  {   //login attempt
            if ($this->controller->verifyLogin() )      // success
                echo $this->mainPage();
            else                                        // fail: incorrect password
                echo $this->loginPage(1);
           }
        elseif ($this->controller->verifyPostLogout() ) {                //logout from mainpage
            $this->controller->logout();
            echo $this->loginPage(2);
        }
        else                                             // regular page; no login attempt or logout
            echo $this->loginPage(0);
    }

    public function loginPage($msg) {
        $header = "<!DOCTYPE html><html><head><link href='https://fonts.googleapis.com/css?family=Asap' rel='stylesheet'><link rel='stylesheet' href='css/login-compact.css'></head><body>";
        $footer = "</body></html>";      
        $page=$header;
        $page=$page."<form id='login' method='post' class='login' action=".htmlspecialchars($_SERVER['PHP_SELF']).">";
        $page=$page."<input name='uname' placeholder='Username'>";
        $page=$page."<input name='passwd' type='password' placeholder='Password'>";
        $session = $this->controller->getSess();
        $page.= "<p style='color:#32CD32;'> Vars:<br> uname: ".$session->getUsername()."<br>pass: ".$session->getPassword()."<br>SID: ".$session->getSessionID()."</p>";
        if ($msg === 1)     // Incorrect login
            $page=$page."<p style='color:#DC143C;' >Incorrect Username or Password</p>";
        elseif ($msg === 2) // Logout successful
            $page=$page."<p style='color:#32CD32;' >Logged Out Successfully</p>";
        else                // No Message at all.  
            $page=$page."<p></p>";

        $page=$page."<button type='submit'>Login</button>";
        $page=$page."</form>";
        $page=$page.$footer;
        return $page;
    }

    public function mainPage() {
        $header = "<!DOCTYPE html><html><head><link href='https://fonts.googleapis.com/css?family=Asap' rel='stylesheet'><link rel='stylesheet' href='css/login-compact.css'></head><body>";
        $footer = "</body></html>";      
        
        $page=$header;

        $page.= "<form id='logout' method='post' class='login' action=".htmlspecialchars($_SERVER['PHP_SELF']).">";
        $page.= "<p style='color:#FFD700;'> Main Page </p><br>";
        $page.= "<p style='color:#FFD700;'> Shrodering<br>Theorem (Godel): <br> Snow <br> phrasals: put off, taken on </p>";
        $session = $this->controller->getSess();
        $page.= "<p style='color:#32CD32;'> Vars:<br> uname: ".$session->getUsername()."<br>pass: ".$session->getPassword()."<br>SID: ".$session->getSessionID()."</p>";
        $page.= "<button name='logout' type='submit'>Logout</button>";
        $page.= "</form>";
        $page.= $footer;
        return $page;
    }
}
?>