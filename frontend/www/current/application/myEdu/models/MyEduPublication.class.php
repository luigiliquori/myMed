<?

/** 
 * Publication  
 * 
 */
class MyEduPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = "myEduPublication";
	public $area;
	public $category;
	public $locality;
	public $organization;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $title;
	public $text;
	
	public $pred1;
	public $pred2;
	
	public $wrapped1;
	public $wrapped2;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"publisher"	=> KEYWORD,
						"type"	=> KEYWORD,
						"category" => KEYWORD,
						"locality" => KEYWORD,
						"organization" => KEYWORD,
						"area" => KEYWORD,
						"title" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS),
				
				// Data attributes 
				array("text" => TEXT),
				
				// Wrapped attributes
				array("title", "end", "wrapped1", "wrapped2"),
				
				$predicateStr);
		
	}
}

?>