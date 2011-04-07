<?php
class Debug
{
	private static $colored = false;// false for PHP > 5.3 (already colored)
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
		return '<pre style="background-color:#FFFFFF;border:solid 1px #C0C0C0;overflow:auto;text-align:left;"><code>'.$tampon.'</code></pre>';
	}
	private /*void*/ static function var_dump_helper_replaceInerQuotes($masque)
	{
		$tampon = preg_replace('#(<span[^<>]*>)|(</span>)#isU','',$masque[0]);
		//$tampon= preg_replace('#\$(\w)+#is','<span style=\'font-weight:bold;\'>$0</span>',$tampon);
		return $tampon;
	}
	public /*void*/ static function trace(/*mixed*/ $var, /*string*/ $name=null, /*string*/ $file=null, /*int*/ $line=null)
	{
		if(defined('DEBUG')&&DEBUG)
		{
			if($name != null)
				echo '$'.$name;
			if($file != null)
				echo '	in '.$file;
			if($line != null)
				echo ' at line '.$line;
			if(self::$directOutput)
				echo self::var_dump($var);
			else
				self::$buffer .= self::var_dump($var);
		}
	}
	public /*void*/ static function printTraces()
	{
		if(defined('DEBUG')&&DEBUG)
			echo self::$buffer;
	}
}
/*void*/ function trace(/*mixed*/ $var, /*string*/ $name=null, /*string*/ $file=null, /*int*/ $line=null)
{
	Debug::trace($var, $name, $file, $line);
}
/*void*/ function printTraces()
{
	Debug::printTraces();
}
?>