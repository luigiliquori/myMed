<?

/** 
 * Comments of Comments
 * 
 */
class Comment extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = "comment";
	public $pred1;
	public $pred2;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $text;
	
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
						"pred1" => KEYWORD,
						"pred2" => GPS),
				
				// Data attributes 
				array("text" => TEXT),
				
				// Wrapped attributes
				array("wrapped1", "wrapped2"),
				
				$predicateStr);
		
	}
}

?>