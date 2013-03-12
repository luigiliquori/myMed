<?

/** 
 * Publication  
 * 
 */
class myEuroCINPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type; // MyEuroCinPublication
	public $data; // Title
	public $Nazione; // City
	public $Lingua;
	public $Arte_Cultura;
	public $Natura;
	public $Tradizioni;
	public $Enogastronomia;
	public $Benessere;
	public $Storia;
	public $Religione;
	public $Escursioni_Sport;
	
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $text;
	public $validated;
	
	
	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"type"	=> KEYWORD,
						"publisher"	=> KEYWORD,
						"begin"	=> KEYWORD,
						"end"	=> KEYWORD,
						"data" => KEYWORD,
						"Nazione" => KEYWORD,
						"Lingua" => KEYWORD,
						"Arte_Cultura" => KEYWORD,
						"Natura" => KEYWORD,
						"Tradizioni" => KEYWORD,
						"Enogastronomia" => KEYWORD,
						"Benessere" => KEYWORD,
						"Storia" => KEYWORD,
						"Religione" => KEYWORD,
						"Escursioni_Sport" => KEYWORD,
						"validated" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => KEYWORD
						),
				
				// Data attributes 
				array("text" => TEXT),
				
				// Wrapped attributes
				array("end"),
				
				$predicateStr);
		
	}
	
	/** Return the title of the publication */
	public function getTitle() {
		return $this->_data;
	}
}

?>