<?

/** 
 * Publication  
 * 
 */
class myEuroCINPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = 'myEuroCINPublication';
	public $locality;
	public $language;
	public $category;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $title;
	public $text;
	public $validated;
	
	public $pred1;
	public $pred2;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"publisher"	=> KEYWORD,
						"type"	=> KEYWORD,
						"locality" => KEYWORD,
						"language" => KEYWORD,
						"category" => KEYWORD,
						"title" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS),
				
				// Data attributes 
				array("text" => TEXT,
					  "validated" => TEXT),
				
				// Wrapped attributes
				array("title", "end"),
				
				$predicateStr);
		
	}
}

?>