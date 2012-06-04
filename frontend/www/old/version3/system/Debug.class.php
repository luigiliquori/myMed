<?php
/**
 * An class to define a trace function printed in a debug console
 */
class Debug
{
	private static $colored = false;// false if html_errors = On on php.ini
	/** true if trace directly print and don't wait for printTraces()*/
	public static $directOutput = false;
	private static $buffer = '';
	/*a colored var_dump*/
	private /*void*/ static function var_dump($var)
	{
		ob_start();
		var_dump($var);
		$tampon = ob_get_contents();
		ob_end_clean();
		if(!self::$colored)
			return $tampon;
		$tampon = preg_replace('#<#i',"&lt;",$tampon);
		$tampon = preg_replace('#=>\n +(bool|int|float|doubles|real numbers|string|null)#i',"=>\t$1",$tampon);
		$tampon = preg_replace('#\(|\)|\[|\]|\{|\}|<|>|=|-|\+|\*|/|\||\.|&#is','<span style=\'color:#huitzerozerozeroFF;\'>$0</span>',$tampon);
		$tampon = preg_replace('#[0-9]+#is','<span style=\'color:#FF8000;\'>$0</span>',$tampon);
		$tampon = preg_replace('`#huitzerozerozeroFF`is','#8000FF',$tampon);
		$tampon = preg_replace('#(bool|int|float|doubles|real numbers|string|null|string|array|object|true|false)#i','<span style=\'font-weight:bold;color:#0000FF;\'>$0</span>',$tampon);
		//$tampon = preg_replace('#"(.*)(((<span[^<>]*>)|(</span>))(.*))*"#isU','"$1$6"',$tampon);
		$tampon = preg_replace_callback('#"(([^"]|\\\\")*)"#isU','Debug::var_dump_helper_replaceInerQuotes',$tampon); 
		$tampon = preg_replace('#"(.*)"#isU','<span style="color:#808080;">$0</span>',$tampon);
		return '<pre style="background-color:#FFFFFF;border:solid 1px #C0C0C0;overflow:auto;text-align:left;font-weight:normal;color:#000;font-size:11px;"><code>'.$tampon.'</code></pre>';
	}
	private /*void*/ static function var_dump_helper_replaceInerQuotes($masque)
	{
		$tampon = preg_replace('#(<span[^<>]*>)|(</span>)#isU','',$masque[0]);
		//$tampon= preg_replace('#\$(\w)+#is','<span style=\'font-weight:bold;\'>$0</span>',$tampon);
		return $tampon;
	}
	/**
	 * trace a varible in the debug console
	 * @param mixed $var	variable to trace
	 * @param string $name	variable's name
	 * @param string $file	must be equals to __FILE__
	 * @param string $line	must be equals to __LINE__
	 */
	public /*void*/ static function trace(/*mixed*/ $var, /*string*/ $name=null, /*string*/ $file=null, /*int*/ $line=null)
	{
		if(defined('DEBUG')&&DEBUG)
		{
			$localBuffer	= '';
			if($name != null)
				$localBuffer .= $name;
			if($file != null)
				$localBuffer .= '	in '.$file;
			if($line != null)
				$localBuffer .= ' at line '.$line;
			if(self::$directOutput)
				echo $localBuffer.self::var_dump($var);
			else
				self::$buffer .= $localBuffer.self::var_dump($var);
		}
	}
	/**
	 * Print trace submited by trace()
	 */
	public /*void*/ static function printTraces()
	{
		if(defined('DEBUG')&&DEBUG)
			echo self::$buffer;
	}
}
/**
 * trace a variable in the debug console
 * @param mixed $var	variable to trace
 * @param string $name	variable's name
 * @param string $file	must be equals to __FILE__
 * @param string $line	must be equals to __LINE__
 */
/*void*/ function trace(/*mixed*/ $var, /*string*/ $name=null, /*string*/ $file=null, /*int*/ $line=null)
{
	Debug::trace($var, $name, $file, $line);
}
/**
 * Print trace submited by trace()
 */
/*void*/ function printTraces()
{
	Debug::printTraces();
}
?>
