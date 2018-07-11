<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(CS_PATH.DS.'dbconnection.php');

class User extends DatabaseObject {
	
	protected static $table_name="user";
	public $ID;
	public $Username;
	public $Password;
	public $GivenName;
	public $Surname;
	public $EmployeeID;
	
  public function full_name() {
    if(isset($this->UserName)) {
      return $this->user_name;
    } else {
      return "";
    }
  }

	public static function authenticate($username="", $password="") 
	{
    	global $database;
    	$username = $database->escape_value($username);
    	$password = $database->escape_value($password);

    	$sql  = "SELECT * FROM user ";
    	$sql .= "WHERE LoginName = '{$username}' ";
    	$sql .= "AND Password = md5('{$password}') ";
    	$sql .= "LIMIT 1";

    	$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	 public static function find_by_id($id=0) {
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
	  }
	  
		// Common Database Methods
		public static function find_all() {
			return self::find_by_sql("SELECT * FROM ".self::$table_name);
	  }
	  
	
	  public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->execute($sql); 
		
		$object_array = array();
		while ($row = $result_set->fetch_array()) {
			
			$object_array[] = self::instantiate($row);
		}
		// echo '<pre>';var_dump($object_array);echo '</pre>';		
		return $object_array;
	  }


	private static function instantiate($record) {
		// Could check that $record exists and is an array
    $object = new self;
		// Simple, long-form approach:
		/*
		 $object->id 		 = $record['ID'];
		 $object->username 	 = $record['Username'];
		 $object->password 	 = $record['Password'];
		 $object->first_name = $record['GivenName'];
		 $object->last_name  = $record['Surname'];
		*/
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	  $object_vars = get_object_vars($this);
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
	}
        
        /*
         * @author: jdymosco
         * @date: May 20, 2013
         * @description: Method that will set user session id and save in database table user_session.
         */
        public static function setUserSession($user_id){
            global $database;
            global $session;
            
            $session_id = session_id();
            //$browser_details = $_SERVER['HTTP_USER_AGENT'];
            //$ipaddress = $_SERVER['REMOTE_ADDR'];
            
            $session->session_set('user_session_id',$session_id);
            $database->execute("INSERT INTO `user_sessions` (`UserID`,`SessionID`,`LoginTime`) 
                                VALUES ($user_id,'$session_id',NOW())
                                ON DUPLICATE KEY UPDATE `LoginTime` = NOW()
                               ");
        }
        
        /*
         * @author: jdymosco
         * @date: May 20, 2013
         * @description: Method that will unset user session id and delete in database table user_session.
         */
        public static function unsetUserSession($user_id){
            global $database;
            global $session;
            
            $session_id = $session->session_get('user_session_id');
            
            if($session_id){
                $database->execute("UPDATE `user_sessions` SET `LogoutTime` = NOW() WHERE `UserID` = $user_id AND `SessionID` = '$session_id'");
                $session->session_unset('user_session_id');
            }
        }
}

?>