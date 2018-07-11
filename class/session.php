<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally 
// inadvisable to store DB-related objects in sessions


class Session 
{
	
	private $logged_in=false;
	public $user_id;
	public $emp_id;
	public $createid; 
	public $prod_add_trans;	
	public $id_add_editOR;
	
	//for Createing MiscTransaction
	public $prod_add_adj;
	public $adj_prodid;
	public $adj_prodcode;
	public $adj_prodname;
	public $adj_soh;
	public $adj_uomid;
	public $adj_uom;
	public $adj_qty;
	public $adj_reasonid;
	public $adj_reason;
		
	function __construct() 
	{
		session_start();
		$this->check_login();
		$this->check_createid();
		
		//inventory - transfer
		$this->check_transfer();
		$this->check_trans_prod();
		$this->check_editOR_ids();
		
		
		
    	if($this->logged_in) 
    	{
      		// actions to take right away if user is logged in
    	} 
    	else 
    	{
      		// actions to take right away if user is not logged in
    	}
	}
	
  	public function is_logged_in() 
  	{	
  		return $this->logged_in;
  	}
  
	public function unset_sessionarrays()
    {   	
   		$_SESSION['prodid']=array();
        $_SESSION['prodcode']=array();
        $_SESSION['prodname']=array();
        $_SESSION['soh']=array();
        $_SESSION['uomid']=array();
        $_SESSION['uom']=array();
        $_SESSION['qty']=array();
        $_SESSION['resid']=array();
        $_SESSION['res']=array();
    }
    
	// inventory - transfer
	private function check_transfer() 
	{
    	if(isset($_SESSION['trans_id'])) 
    	{
	      	$this->trans_id = $_SESSION['trans_id'];
	      	$this->trans_qty = $_SESSION['trans_qty'];
	      	$this->trans_uom = $_SESSION['trans_uom'];
			$this->trans_reason = $_SESSION['trans_reason'];
    	} 
    	else 
    	{
	      	unset($this->trans_id);
	     	unset($this->trans_qty);
	      	unset($this->trans_uom);
			unset($this->trans_reason);
    	}
  	}
  
	// inventory - transfer
  	public function unset_transfer() 
  	{
  		unset($_SESSION['trans_id']);
		unset($this->trans_id);
  		unset($_SESSION['trans_qty']);
		unset($this->trans_qty);
  		unset($_SESSION['trans_uom']);
		unset($this->trans_uom);
  		unset($_SESSION['trans_reason']);
		unset($this->trans_reason);
  	} 
  
    public function unset_adjustment() 
    {
  		unset($_SESSION['adj_id']);
		unset($this->adj_id);
  		unset($_SESSION['adj_qty']);
		unset($this->adj_qty);
  		unset($_SESSION['adj_uom']);
		unset($this->adj_uom);
  		unset($_SESSION['adj_reason']);
		unset($this->adj_reason);
  	} 
  
	public function login($user) 
	{
    	// database should find user based on username/password
    	if($user)
    	{
      		$this->user_id = $_SESSION['user_id'] = $user->ID;
      		$this->emp_id = $_SESSION['emp_id'] = $user->EmployeeID;
      		$this->logged_in = true;
    	}
  	}
  
  	public function logout() 
  	{
            /*
             * @author: jdymosco
             * @date: Feb 20, 2013
             * @explanation: Added unsetting of Branch session when logout.
             */
            unset($_SESSION['Branch']);
            //EOL of added...
            
            unset($_SESSION['user_id']);
            unset($_SESSION['emp_id']);
            unset($this->user_id);
            unset($this->emp_id);
            $this->logged_in = false;
            $this->unset_createid();
  	}

  	private function check_login() 
  	{
    	if(isset($_SESSION['user_id'])) 
    	{
      		$this->user_id = $_SESSION['user_id'];
      		$this->emp_id = $_SESSION['emp_id'];
      		$this->logged_in = true;
    	} 
    	else 
    	{
      		unset($this->user_id);
      		unset($this->emp_id);
      		$this->logged_in = false;
    	}
  	}
  
  	public function check_createid()
  	{
    	if(isset($_SESSION['createid'])) 
    	{
      		$this->createid = $_SESSION['createid'];
    	} 
    	else 
    	{
      		unset($this->createid);
    	}
  	}

  	public function set_createid($createid)
  	{
	 	if($createid) 
	 	{
			$this->createid = $_SESSION['createid'] = $createid; 
	 	}
	 	else 
	 	{
			unset($this->createid);
	 	}
	}

  	public function unset_createid() 
  	{
  		unset($_SESSION['createid']);
		unset($this->createid);
  	} 
  
	public function set_transfer($trans_id,
  							   $trans_qty, 
  							   $trans_uom, 
  							   $trans_reason)
   	{
		if($trans_id) 
	 	{
		 	$this->trans_id = $_SESSION['trans_id'] = $trans_id; 
		 	$this->trans_qty = $_SESSION['trans_qty'] = $trans_qty; 
		 	$this->trans_uom = $_SESSION['trans_uom'] = $trans_uom; 
		 	$this->trans_reason = $_SESSION['trans_reason'] = $trans_reason; 
     	}
	 	else 
	 	{
			unset($this->trans_id);
		 	unset($this->trans_qty);
		 	unset($this->trans_uom);
		 	unset($this->trans_reason);
	 	}
	}
  
	public function set_adj_prod($prodid, $prodcode, $prodname, $soh, $uom, $qty, $reason)
  	{
		if($prodid) 
	 	{	 	
		 	$this->prod_add_adj[] = $_SESSION['prod_add_adj'][] = array
		 	(				
					'ProductID'		=> $prodid ,
					'ProductCode' 	=> $prodcode,
					'ProductName' 	=> $prodname,
					'SOH' 			=> $soh,
					'UOM'		    => $uom,
					'Qty' 			=> $qty,
					'Reason' 	    => $reason
	
			);
	 	}
	 	else 
	 	{
			unset($this->prod_add_adj);
	 	}
  	}
  
 	public function set_adjustment($adj_prodid, $adj_prodcode, $adj_prodname, $adj_soh, $adj_uomid, $adj_uom, $adj_qty, $adj_reasonid, $adj_reason)
  	{
       
	 	if($adj_prodid) 
	 	{ 
	     	$this->adj_prodid = $_SESSION['adj_prodid'] = $adj_prodid; 
		 	$this->adj_prodcode = $_SESSION['adj_prodcode'] = $adj_prodcode; 
		 	$this->adj_prodname = $_SESSION['adj_prodname'] = $adj_prodname;
		 	$this->adj_soh = $_SESSION['adj_soh'] = $adj_soh; 
		 	$this->adj_uomid = $_SESSION['adj_uomid'] = $adj_uomid;
		 	$this->adj_uom = $_SESSION['adj_uom'] = $adj_uom; 
		 	$this->adj_qty = $_SESSION['adj_qty'] = $adj_qty; 
		 	$this->adj_reasonid = $_SESSION['adj_reasonid'] = $adj_reason;
		 	$this->adj_reason = $_SESSION['adj_reason'] = $adj_reason; 		 
	 	}
	 	else 
	 	{
	     	unset($this->adj_prodid);
		 	unset($this->adj_prodcode);
		 	unset($this->adj_prodname);
		 	unset($this->adj_soh);
		 	unset($this->adj_uomid);
		 	unset($this->adj_uom);
		 	unset($this->adj_qty);
		 	unset($this->adj_reasonid);
		 	unset($this->adj_reason);
	 	}
  	}
  
  	public function set_trans_prod_qty($pointer, 
  									 $trans_qty,
  									 $trans_reason)
  	{
	 	if($trans_qty) 
	 	{	 	
			$this->prod_add_trans[$pointer]['Qty'] = $_SESSION['prod_add_trans'][$pointer]['Qty'] = $trans_qty;
		 	$this->prod_add_trans[$pointer]['Reason'] = $_SESSION['prod_add_trans'][$pointer]['Reason'] = $trans_reason;					 
	 	}
	 	else 
	 	{
		 	unset($this->prod_add_trans);
	 	}
  	}
  
  	//inventory - transfer 
	public function set_trans_prod($trans_id,
								   $trans_prodid,
								   $trans_prodcode, 
								   $trans_prodname, 
								   $trans_soh, 
								   $trans_uom, 
								   $trans_qty, 								 
								   $trans_reason)
	{
		if($trans_id)
		{	 	
			$this->prod_add_trans[] = $_SESSION['prod_add_trans'][] = array(				
						'InventoryID'	=> $trans_id,
						'ProductID'		=> $trans_prodid ,
						'ProductCode' 	=> $trans_prodcode,
			 			'ProductName' 	=> $trans_prodname,
						'SOH' 			=> $trans_soh,
						'UOM' 			=> $trans_uom,
						'Qty' 			=> $trans_qty,
						'Reason' 		=> $trans_reason
			 			 );
			  
		}
	 	else 
	 	{
			unset($this->prod_add_trans);
	 	}
	}
  
	private function check_trans_prod()
  	{
    	if(isset($_SESSION['prod_add_trans'])) 
    	{
			$this->prod_add_trans = $_SESSION['prod_add_trans'];
    	}
    	else 
    	{
			unset($this->prod_add_trans);
    	}
  	}
  
 	public function unset_trans_prod()
 	{
		unset($_SESSION['prod_add_trans']);
	 	unset($this->prod_add_trans);
 	}
 
 	//EDIT OR ###############################################################################
	// sales - edit OR
  	public function unset_editOR() 
  	{
  		unset($_SESSION['editOR_id']);
		unset($this->editOR_id);
	  	unset($_SESSION['editOR_refType']);
		unset($this->editOR_refType);
	  	unset($_SESSION['editOR_outstandingBalance']);
		unset($this->editOR_outstandingBalance);  	
		unset($_SESSION['editOR_amount']);
		unset($this->editOR_amount);  		
		unset($_SESSION['editOR_refId']);
		unset($this->editOR_refId);  
		unset($_SESSION['editOR_igsCode']);
		unset($this->editOR_igsCode);  
		unset($_SESSION['editOR_txnDate']);
		unset($this->editOR_txnDate);  
		unset($_SESSION['editOR_txnAmount']);
		unset($this->editOR_txnAmount);  
		unset($_SESSION['editOR_creditTerm']);
		unset($this->editOR_creditTerm);
	} 
  
	public function set_editOR($editOR_id,
  							   $editOR_refType, 
  							   $editOR_outstandingBalance,
  							   $editOR_amount,
  							   $editOR_refId,
  							   $editOR_igsCode,
  							   $editOR_txnDate,
  							   $editOR_txnAmount,
  							   $editOR_creditTerm  							   
  							   )
	  {
	  
	  
		 if($editOR_id) 
		 {
			 $this->editOR_id = $_SESSION['editOR_id'] = $editOR_id; 
			 $this->editOR_refType = $_SESSION['editOR_refType'] = $editOR_refType; 
			 $this->editOR_outstandingBalance = $_SESSION['editOR_outstandingBalance'] = $editOR_outstandingBalance; 
			 $this->editOR_amount = $_SESSION['editOR_amount'] = $editOR_amount; 
			 
			 $this->editOR_refId = $_SESSION['editOR_refId'] = $editOR_refId; 
			 $this->editOR_igsCode = $_SESSION['editOR_igsCode'] = $editOR_igsCode;
			 $this->editOR_txnDate = $_SESSION['editOR_txnDate'] = $editOR_txnDate;
			 $this->editOR_txnAmount = $_SESSION['editOR_txnAmount'] = $editOR_txnAmount;
			 $this->editOR_creditTerm = $_SESSION['editOR_creditTerm'] = $editOR_creditTerm;
	     }
		 else 
		 {
			 unset($this->editOR_id);
			 unset($this->editOR_refType);
			 unset($this->editOR_outstandingBalance);	
			 unset($this->editOR_amount);			 
			 unset($this->editOR_refId);
			 unset($this->editOR_igsCode);
			 unset($this->editOR_txnDate);
			 unset($this->editOR_txnAmount);
			 unset($this->editOR_creditTerm);
		 }
	  }
  
	private function check_editOR() 
	{
    	if(isset($_SESSION['editOR_id'])) 
    	{
      		$this->editOR_id = $_SESSION['editOR_id'];
      		$this->editOR_refType = $_SESSION['editOR_refType'];
      		$this->editOR_outstandingBalance = $_SESSION['editOR_outstandingBalance'];  
      		$this->editOR_amount = $_SESSION['editOR_amount'];  
      		$this->editOR_refId = $_SESSION['editOR_refId']; 
      		$this->editOR_igsCode = $_SESSION['editOR_igsCode']; 
      		$this->editOR_txnDate = $_SESSION['editOR_txnDate']; 
      		$this->editOR_txnAmount = $_SESSION['editOR_txnAmount']; 
      		$this->editOR_creditTerm = $_SESSION['editOR_creditTerm'];
    	}
     	else 
     	{
      		unset($this->editOR_id);
      		unset($this->editOR_refType);
      		unset($this->editOR_outstandingBalance);     
      		unset($this->editOR_amount); 
      		unset($this->editOR_refId);  
      		unset($this->editOR_igsCode);  
      		unset($this->editOR_txnDate);  
      		unset($this->editOR_txnAmount);  
      		unset($this->editOR_creditTerm);
    	}
  	}
  

	public function set_editOR_ids_amount($pointer, $editOR_amount)
  	{
		if($editOR_amount) 
	 	{	 	
		 	$this->id_add_editOR[$pointer]['Amount'] = $_SESSION['id_add_editOR'][$pointer]['Amount'] = $editOR_amount;						 
	 	}
	 	else 
	 	{
		 	unset($this->id_add_editOR);
	 	}
  	}

	//inventory - editORfer 
	public function set_editOR_ids($editOR_id,
								   $editOR_refType,
								   $editOR_outstandingBalance,
								   $editOR_amount,
								   $editOR_refId,
  							   	   $editOR_igsCode,
  							       $editOR_txnDate,
  							       $editOR_txnAmount,
  							       $editOR_creditTerm  	
								   )
	{
	 	
		 if($editOR_id)
		 {	 	
			 $this->id_add_editOR[] = $_SESSION['id_add_editOR'][] = array(				
						'ID'					=> $editOR_id,
						'RefType'				=> $editOR_refType,
						'OutStandingBalance' 	=> $editOR_outstandingBalance,
			 			'Amount' 				=> $editOR_amount,
			 			'RefId' 				=> $editOR_refId	,
			 			'IgsCode' 				=> $editOR_igsCode	,
			 			'TxnDate' 				=> $editOR_txnDate	,
			 			'TxnAmount' 			=> $editOR_txnAmount,
			 			'WithinCreditTerm'		=> $editOR_creditTerm
			 			 );
		 }
		 else 
		 {
			 unset($this->id_add_editOR);
		 }
	}

	private function check_editOR_ids()
  	{
		if(isset($_SESSION['id_add_editOR'])) 
    	{
      		$this->id_add_editOR = $_SESSION['id_add_editOR'];
    	}
    	else 
    	{
      		unset($this->id_add_editOR);
    	}
	}

 	public function unset_editOR_ids()
 	{
		unset($_SESSION['id_add_editOR']);
	 	unset($this->id_add_editOR);
 	}
        
        /*
         * @author: jdymosco
         * @date: May 20, 2013
         * @description: Added methods for session class..
         */
        public function session_set($session_key,$session_value){
            $_SESSION[$session_key] = $session_value;
        }
        
        public function session_unset($session_key){
            if(isset($_SESSION[$session_key])) unset($_SESSION[$session_key]);
            else return false;
        }
        
        public function session_get($session_key){
            if(isset($_SESSION[$session_key])) return $_SESSION[$session_key];
            else return false;
        }
        //EOC for the added methods....
}

$session = new Session();
?>