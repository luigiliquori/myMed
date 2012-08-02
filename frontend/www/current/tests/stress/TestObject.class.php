<?

/** Example showing the use of GenericDataBean */
class TestObject extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $pred1;
	public $pred2;
	public $pred3;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $data;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"pred1" => KEYWORD,
						"pred2" => KEYWORD,
						"pred3" => KEYWORD),
				
				// Data attributes 
				array("data" => TEXT),
				
				// Wrapped attributes
				array("data"),
				
				$predicateStr);
	}
}

?>