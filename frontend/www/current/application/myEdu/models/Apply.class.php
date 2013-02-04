<?

/** 
 * Comments of Applies
 * 
 */
class Apply extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = "apply";
	public $pred1;
	public $pred2;
	public $pred3;
	public $teacher;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $accepted;
	
	public $title;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"publisher"	=> KEYWORD, // student ID
						"type"	=> KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS,
						"pred3" => KEYWORD, // pred of the publication
						"teacher" => KEYWORD), // publication author ID
				
				// Data attributes 
				array("text" => TEXT),
				
				// Wrapped attributes
				array("accepted", "title"),
				
				$predicateStr);
		
	}
}

?>