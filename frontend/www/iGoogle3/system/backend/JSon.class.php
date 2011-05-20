<?php
abstract class JSon
{
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
		return json_encode($this);
	}
}
?>
