<?php


class View {

    public function __construct()
    {

    }

    public function loginPage($msg) 
    {
        $page = $this->header();
        $page.= "<form id='login' method='post' class='login' action=".htmlspecialchars($_SERVER['PHP_SELF']).">";
        $page.= "<input name='uname' placeholder='Username'>";
        $page.= "<input name='passwd' type='password' placeholder='Password'>";

        if ($msg === 1)     // Login Unsuccessful
            $page.= "<p style='color:#DC143C;' >Incorrect Username or Password</p>";
        elseif ($msg === 2) // Logout Successful
            $page.= "<p style='color:#32CD32;' >Logged Out Successfully</p>";
        else                // Neither: for initial form display
            $page.= "<p></p>";
        $page.= "<button type='submit'>Login</button>";
        $page.= "</form>";
        $page.=$this->footer();
        return $page;
    }

    public function mainPage() 
    {   
        $page = $this->header();
        $page.= "<form id='logout' method='post' class='login' action=".htmlspecialchars($_SERVER['PHP_SELF']).">";
        $page.= "<h3 style='color:#FFD700;'>Main Page </h3>";
        $page.= "<br><br><br><br><br><br>";
        $page.= "<p style='color:#bfdceb;'> </p>";
        $page.= "<p style='color:#bfdceb;'> Main page content</p>";
        $page.= "<p style='color:#bfdceb;'> </p>";
        $page.= "<p></p><p><br></p>";
        $page.= "<button name='loggedout' type='submit' value='1'>Logout</button>";
        $page.= "</form>";
        $page.= $this->footer();
        return $page;
    }

    private function header()
    {
        $header = "<!DOCTYPE html><html><head>";
        $header.= "<link href='https://fonts.googleapis.com/css?family=Asap' rel='stylesheet'>";
        $header.= "<link href='css/login-compact.css' rel='stylesheet'></head><body>";
        return $header;
    }

    private function footer()
    {
        $footer = "</body></html>";      
        return $footer;
    }

    public static function print_rr($title, $obj)
    {
        echo "<BR><p style='color:white'>".$title."</p>";
        echo "<PRE><p style='color:white'>";
        print_r($obj);
        echo "</p></PRE>";
    }
}

?>