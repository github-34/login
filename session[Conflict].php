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
	
 	// POST Variables Set
 	private $sessionid;
 	private $username; 
	private $password;
	private $logout;
	
	//Session
	private $access_time;
	private $model;
	private $sessid;
	
 	public function __construct($model) {
 		$this->model = $model;
 		$this->access_time = time();
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
    	$this->sessid = session_id();
    	return true;
	}

	public function close(){
		return true;  
	}
	public function destroy($session_id) {
		$this->model->deleteSession($session_id);
		unset($_SESSION['uname']);
        unset($_SESSION['passwd']);
        unset($_SESSION['logout']);
        unset($_SESSION);
        session_detroy();
	}
	public function read($session_id){
	//	$data = $this->model->readSession($sessionId);
		$data = "";
    	if ($data !== false)
    		return $data;
    	else 
    		return '';
	}
	public function write($session_id, $data) {
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
	public function postVars() {
		//        session_start();
        if ( isset($_SESSION['session_id']) )
        	$this->sessionid=session_id();//$_SESSION['session_id'];

        // Set Post class variables 
		if ( isset($_POST['uname']) and isset($_POST['passwd'])) {
             $this->username = $_POST['uname'];
             $this->password = $_POST['passwd'];
        }
        if ( isset($_POST['logout']) )
            $this->logout = $_POST['logout'];
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