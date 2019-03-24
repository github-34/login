<?php

/* 
	SessionHandlerInterface:  
		interface which defines a prototype for creating a custom 
		session handler, in thise case one that stores session date in a database
 Methods */

/*
interface  SessionHandlerInterface {
	abstract public close ( void ) : bool
	abstract public destroy ( string $session_id ) : bool
	abstract public gc ( int $maxlifetime ) : int
	abstract public open ( string $save_path , string $session_name ) : bool
	abstract public read ( string $session_id ) : string
	abstract public write ( string $session_id , string $session_data ) : bool
}*/

class MySessionHandler implements SessionHandlerInterface {
	

	private $model;

 	// POST Variables Set
	private $username; 
	private $password;
	private $logout;
	
	//Session
	private $sessid;
	private $access_time;
	
	
 	public function __construct($model) {
 		$this->model = $model;
 //		$this->sessid = session_id();
 //		echo "Session Handler Constructor: sssid ".$this->sessid."----";
	}

	/* 
		SESSION HANDLER IMPLEMENTATION 

	*/

	/**
   	* Opens a session; executed when session started automatically or manually with session_start();
   	* @param string $savePath; local path for storing session in files; not used in this implementation since everything stored in DB; 
   	* @param string $session_name; ???
   	* @return boolean
   */
	public function open($save_path, $session_name) {  
		$savePath = '';
    	$sessionName = '';
    	//$this->sessid = session_id();
    	//echo "Session Handler Open: sssid ".$this->sessid."----";
    	return true;
	}

	public function close(){
		echo "Session Handler Closed: sssid ".$this->sessid."----";
		return true;  
	}
	public function destroy($session_id) {
		echo "Session Handler Destroy: sssid ".$this->sessid."----";
		$this->model->deleteSession($session_id);
		unset($_SESSION['uname']);
        unset($_SESSION['passwd']);
        unset($_SESSION['logout']);
        unset($_SESSION);
        session_detroy();
	}
	public function read($session_id){
		/*echo "Session Handler ReaD:";
		echo "Session Handler ReaD: sssid ".$this->sessid.", session_id".$session_id."----";
		$data = $this->model->readSession($session_id);
		if ($data !== false)
    		return $data;
    	else */
    		return '';
	}
	public function write($session_id, $data) {
		echo "Session Handler Write: sssid ".$this->sessid."-";
//		print_r($data);
		if (isset($session_id))
			$this->model->addSession($this->sessid, $data);
		return true;
	}
	// Garbage Collection: 
	public function gc($maxlifetime) {
		return 1;
	}

	/*
		 SESSION FUNCTIONS 

	*/
	public function initialize() {

		if ( isset($_POST['uname']) )
             $this->username = $_POST['uname'];
        if (  isset($_POST['passwd']) )
        	 $this->password = $_POST['passwd'];
        if ( isset($_POST['logout']) )
            $this->logout = $_POST['logout'];

        if ( isset($_SESSION['session_id']))
        	$this->sessid=session_id();

        echo "Session Initialized: un:".$this->username."pass:".$this->password."logout:".$this->logout."session id:".$this->sessid."----";
        // Erase Session data
    //    print_r($_SESSION);
      //  print_r($_POST);

     //   if ( isset($_POST['uname']) & isset($_POST['passwd']) ) {
       // 	session_regenerate_id();
  //      	sessionid();
        //}

	}

	public function getUsername() {
		//return isset($_POST['uname']);
		return $this->username;
	}
	public function getPassword() {
		return $this->password;
		//return isset($_POST['passwd'])
	}
	public function getLogout() {
		return $this->logout;
		//return isset($_POST['passwd'])
	}
	public function getSessionID() {
		return session_id();
	}
}
?>