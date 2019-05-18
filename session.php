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
	
 	public function __construct($model)
 	{
 		$this->model = $model;
 	}

	/* 
		SESSION HANDLER IMPLEMENTATION 

	*/

	/**
   	* Opens a session; executed when session started automatically or manually with session_start();
   	* @param string $savePath; local path for storing session in files; not used in this implementation since everything stored in DB; 
   	* @param string $session_name; ???
   	* @return boolean
   	**/
	public function open($save_path, $session_name)
	{
    	if (DEBUG) View::print_rr("-------Session Handler Open: savepath:", $save_path);  
    	if (DEBUG) View::print_rr("-------Session Handler Open: session_name:", $session_name);  
    	
    	return true;
	}

	public function read($session_id)
	{
		if ( isset($_POST['uname']) )
             $this->username = $_POST['uname'];
        if (  isset($_POST['passwd']) )
        	 $this->password = $_POST['passwd'];
        if ( isset($_POST['logout']) )
            $this->logout = $_POST['logout'];

        if ( isset($_SESSION['session_id']))
        	$this->sessid=session_id();

		$rows = $this->model->readSession($session_id);

		/*if ( sizeof($rows) === 1) 
			$ret = implode('**',$rows);
		elseif (sizeof($rows) > 1) 
			$ret = implode('**',$rows[0]);
		else*/
		$ret = '';
	
		if (DEBUG) View::print_rr("-------Session Handler Read: session_id:", $session_id);  
		if (DEBUG) View::print_rr("-------Session Handler Read: rows:", $rows);  
		
		return $ret; 
	}

	public function write($session_id, $data)
	{
		if (DEBUG) View::print_rr("-------Session: write (sessionid)", $session_id);  
		if (DEBUG) View::print_rr("-------Session: write (data)", $data);  
		
		if (isset($session_id))
			$this->model->addSession($session_id, $data);
		return true;
	}

	public function close()
	{
		if (DEBUG) View::print_rr("-------Session: close",'');  
		
		return true;  
	}

	public function destroy($session_id)
	{
		if (DEBUG) View::print_rr("---Controller: logout: DESTROYING SESSION: ", $session_id);
		//session_destroy(); //$this->sess->destroy($sess->getSessionID());
		
		$this->model->deleteSession($session_id);
		unset($_SESSION['uname']);
        unset($_SESSION['passwd']);
        unset($_SESSION['logout']);
        unset($_SESSION);
        session_detroy();
	}

	// Garbage Collection: 
	public function gc($maxlifetime)
	{
		return 1;
	}
	
	/*
		 SESSION FUNCTIONS 

	*/
	public function outputSession()
	{
        if (DEBUG) View::print_rr("output session: Session Initialized::",$this->sessid); //.$this->username."pass:".$this->password."logout:".$this->logout."session id:".$this->sessid."----";
	}

	public function getUsername()
	{
		//return isset($_POST['uname']);
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
		//return isset($_POST['passwd'])
	}

	public function getLogout()
	{
		return $this->logout;
		//return isset($_POST['passwd'])
	}

	public function getSessionID()
	{
		return session_id();
	}

}
?>