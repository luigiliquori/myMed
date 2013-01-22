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
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $title;
	public $text;

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
						"area" => KEYWORD,
						"title" => KEYWORD),
				
				// Data attributes 
				array(
						"text" => TEXT),
				
				// Wrapped attributes
				array("title", "end"),
				
				$predicateStr);
		
	}
}

?>