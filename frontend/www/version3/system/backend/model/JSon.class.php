<?php
class Undefined
{
	private static $instance = null;
	private function __construct(){}
	public static function getInstance()
	{
		if(!self::$instance)
			self::$instance	= new Undefined();
		return self::$instance;
	}
	public function __toString()
	{
		return 'undefined';
	}
}
abstract class JSon
{
	/**
	 * Construit un objet non initialisé
	 * __construct();
	 * Construit l'objet à partir de __set_state(array)
	 * @param $arg tableau contenant au moins la liste des attribut de l'objet
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
		else
		{
			//on initialise à Undefined et non null pour ne pas modifier les champs de la base si ils ne sont pas défini par la suite
			$class	= new ReflectionObject($this);
			$props	= $class->getProperties();
			foreach($props as $prop)
			{
				$propName	= $prop->getName();
				$this->$propName = Undefined::getInstance();
			}
		}
	}
	
	protected function construct(array $array)
	{
		foreach($array as $key=>$value)
			$this->$key	= $value;
	}
	
	public static function __set_state(array $array)
	{
		return new Profile($array);
	}
	
	public function __set($name, $value)
	{
		trigger_error('attribut '.$name.' didn\'t exists', E_USER_WARNING);
		$this->$name = $value;
	}
	
	public /*string*/ function __toString()
	{
		$o	= clone $this;
		//on supprime les champs undefined (en JS=>JSon un attribut undefined n'existe pas)
		$class	= new ReflectionObject($this);
		$props	= $class->getProperties();
		foreach($props as $prop)
		{
			$propName	= $prop->getName();
			if($o->$propName instanceof Undefined)
			unset($o->$propName);
		}
		return json_encode($o);
	}
}
?>
