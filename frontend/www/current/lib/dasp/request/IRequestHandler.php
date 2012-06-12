<?php 
/**
 *
 * Represent a requet handler
 * @author lvanni
 *
 */
interface IRequestHandler {

	/**
	 * handle POST and GET Request
	 */
	public /*String*/ function handleRequest();
	
	public /*void*/ function setError($message);
	
	public /*void*/ function setSuccess($message);

}
?>