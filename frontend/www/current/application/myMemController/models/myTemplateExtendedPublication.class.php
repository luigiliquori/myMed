<?

/** 
 * Publication  
 * 
 */
class myTemplateExtendedPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = 'myTemplateExtendedPublication';
	public $area;
	public $category;
	public $organization;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $title;
	public $text;
	public $maxappliers;			// Max appliers to a course
	public $currentappliers;		// Current appliers to a course
	
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
						"category" => KEYWORD,
						"organization" => KEYWORD,
						"area" => KEYWORD,
						"title" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS),
				
				// Data attributes 
				array("text" => TEXT, 
					  "maxappliers" => TEXT,
					  "currentappliers" => TEXT),
				
				// Wrapped attributes
				array("title", "end"),
				
				$predicateStr);
		
	}
}

?>