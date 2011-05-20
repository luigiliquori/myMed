<?php
abstract class JSon
{
	/**
	 * Array of ignored properties for initialisation
	 * properties are added as key of array
	 * ex :
	 * protected static $ingnoredProperties = array('mymedID'=>true);
	 */
	protected static /*array*/ $ingnoredProperties = Array();
	/**
	 * Construit un objet non initialisé
	 * __construct();
	 * Construit l'objet à partir de __set_state(array)
	 * @param $arg tableau contenant au moin la liste des attribut de l'objet
	 * __construct(array $arg);
	 * Construit l'objet à partir d'un JSon
	 * @param $arg JSon corespondant à l'objet
	 * __construct(string);
	 */
	public function __construct($arg=null)
	{
		if($arg !== null)
		{
			if(is_array($arg))
				static::construct($arg);
			else
				static::construct(json_decode($arg, true));
		}
	}
	
	private function construct(array $array)
	{
		$Profile = new ReflectionObject ($this);
		$props	= $Profile->getProperties(ReflectionProperty::IS_PUBLIC|ReflectionProperty::IS_PROTECTED|ReflectionProperty::IS_PRIVATE);
		foreach($props as $prop)
		{
			if( (!isset(static::$ingnoredProperties[$prop->getName()])) && (!$prop->isStatic()) )
			{
				$prop->setAccessible(true);
				$prop->setValue($this, @$array[$prop->getName()]);
			}
		}
	}
	
	public static function __set_state(array $array)
	{
		return new Profile($array);
	}
	
	public /*string*/ function __toString()
	{
		return json_encode($this);
	}
}
?>
