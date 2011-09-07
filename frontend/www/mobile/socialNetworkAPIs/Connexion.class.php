<?php
/**
 * A class to define a type of login to myMed
 * @author blanchard
 */
abstract class Connexion
{
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags(){}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print the connexion's button
	 */
	public abstract /*void*/ function button();
	/**
	 * Function called by redirectAfterConnection() to remove GET variables from URL of redirection
	 */
	protected /*void*/ function cleanGetVars(){}
	/**
	 * Permet de rediriger vers la page visitée avant la connexion\n
	 * Redirect to the page visited before login
	 */
	protected /*void*/ function redirectAfterConnection()
	{
// 		try
// 		{
// 			$request = new ProfileRequest;
// 			try
// 			{
// 				$user = $request->read($_SESSION['user']->id);
// 				trace($_SESSION['user'], '$_SESSION[\'user\']', __FILE__, __LINE__);
// 				trace($user, '$request->read($_SESSION[\'user\']->id)', __FILE__, __LINE__);
// 				trace($user->equals($_SESSION['user']), '$user->equals($_SESSION[\'user\'])', __FILE__, __LINE__);
// 				if(!$user->equals($_SESSION['user']))
// 				{
// 					$request->update($_SESSION['user']);
// 					trace('user update done');
// 					$_SESSION['user']	= $request->read($_SESSION['user']->id);
// 				}
// 				else
// 					$_SESSION['user']	= $user;
// 			}
// 			catch(BackendRequestException $ex)
// 			{
// 				if($ex->getCode() != 404)
// 					throw $ex;
// 				$_SESSION['user'] = $request->create($_SESSION['user']);
// 				trace('user creation done');
// 			}
// 			trace($_SESSION['user'], '$_SESSION[\'user\']', __FILE__, __LINE__);
		
// 			static::cleanGetVars();
// 			$query_string	= http_build_query($_GET);
// 			if($query_string != "")
// 				$query_string = '?'.$query_string;
			
// 			httpRedirect('http://'.$_SERVER['HTTP_HOST'].preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']).$query_string);
// 		}
// 		catch(Exception $ex)
// 		{
// 			unset($_SESSION['user']);
// 			if(defined('DEBUG')&&DEBUG)
// 				printTraces();
// 			throw $ex;
// 		}
	}
}
?>
