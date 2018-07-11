<?php
/*   
  @modified by John Paul Pineda.
  @date November 26, 2012.
  @email paulpineda19@yahoo.com         
*/

//require_once(CS_PATH.DS.'config.php');

class DBConnection {

  private $_mysqli=null;
  private $_debug=true;
  
  function __construct() {
  
    $this->open();
  }
  
  function __destruct() {
  
    $this->close();
  }
  
  function open() {
  
    // Initialize or mysql object.
    if(empty($this->_mysqli)) {
    
      $this->_mysqli=new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      
      // Check the connection.
      if(mysqli_connect_errno()) {
      
        throw new Exception('Unable to connect to the MySQL server: '.mysqli_connect_error(), mysqli_connect_errno());				
      }
    }
  }
  
  function close() {
  
    return $this->_mysqli->close();
  }
  
  function execute($query) {    
    
    $result=$this->_mysqli->query($query);		
    $this->clearStoredResults();
    
    if(!$result) {                
      
      throw new Exception(('Unable to execute query: "'.$query.'". '.$this->_mysqli->error), -1);
    } else return $result;		
  }
  
  function insert_id() {
  
    return $this->_mysqli->insert_id;  
  }
  
  function clearStoredResults() {
  
    while($this->_mysqli->more_results() && $this->_mysqli->next_result()) {
    
      if($result=$this->_mysqli->store_result()) $result->free();      
    }
  }
  
  function beginTransaction() {
  
    return $this->_mysqli->autocommit(FALSE);
    // return $this->_mysqli->query("START TRANSACTION");
  }
  
  function commitTransaction() {
  
    $this->_mysqli->commit();
    $this->_mysqli->autocommit(TRUE);
    // $this->_mysqli->query("COMMIT");
  }
  
  function rollbackTransaction() {
  
    return $this->_mysqli->rollback();
    $this->_mysqli->autocommit(TRUE);
    // $this->_mysqli->query("ROLLBACK");
  }
  
  function escape_value($value) {
  
    // PHP v4.3.0 or higher
    if($this->real_escape_string_exists) {
     
      // Undo any magic quote effects so mysql_real_escape_string can do the work.
      if($this->magic_quotes_active) $value=stripslashes($value); 
      
      $value=mysqli_real_escape_string($value);
    } else { 
    
      // Before PHP v4.3.0
      
      // If magic quotes aren't already on then add slashes manually.
      if(!$this->magic_quotes_active) $value=addslashes($value); 
      
      // If magic quotes are active, then the slashes already exist.
    }
    return $value;
  }
}

$database=new DBConnection();
$db=& $database;
?>

