<?php
// @todo warning $_SESSION['user'] is now an Array
/**
 * A class to define a type of login to myMed
 * @author blanchard
 */
abstract class Connexion
{
	/**
	 * Print content's tags to be put inside <head> tag
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
	protected /*void*/ function cleanGetVars(){}
	/**
	 * Permet de rediriger vers la page visitée avant la connexion
	 * Redirect to the page visited before login
	 */
	protected /*void*/ function redirectAfterConnection()
	{
		$request = new ProfileRequest;
		try
		{
			$user = $request->read($_SESSION['user']->mymedID);
			if(!$user->equals($_SESSION['user']))
				$request->update($_SESSION['user']);
			///@todo opdate local Session var
		}
		catch(BackendRequestException $ex)
		{
			if($ex->getCode() != 404)
				throw $ex;
			$_SESSION['user'] = $request->create($_SESSION['user']);
		}
		
		static::cleanGetVars();
		$query_string	= http_build_query($_GET);
		if($query_string != "")
			$query_string = '?'.$query_string;
		header('Location:'.$_SERVER["SCRIPT_NAME"].$query_string);
		exit;
	}
}
?>