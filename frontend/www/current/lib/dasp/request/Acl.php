<?php

/*
 * ACL = Access Control List
 */

class Acl {
	
	protected $target = null; 
	protected $acl = null;
	/*
	 * $acl = array  (stored as $_SESSION['user']->acl) , 
	 * 
	 * contains the method that you are authorized to call
	 * 
	 *   array('defaultMethod', 'read') for guests,
	 *   array('defaultMethod', 'read', 'delete', 'update', 'insert') for users logged in
	 */
	
	
	public function __construct( $target, $acl ){
		$this->target = $target;
		$this->acl = $acl;
	}

	public function __call( $method, $arguments ){
		if ( method_exists( $this->target, $method ) && in_array($method, $this->acl) ){
			
			return call_user_func_array(
				array( $this->target, $method ),
				$arguments
			);
		} else {
			$this->target->setError(_("Please Log in to be able to access this feature"));
			// don't hesitate to override and pass a better message from your controller
			debug_r($arguments);
			$this->target->error($arguments);
		}
	}
}