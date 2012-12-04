<?

/** Example showing the use of GenericDataBean */
class Partnership extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = "partnership";
	public $theme;
	public $other;
	
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
						"type"	=> KEYWORD,
						"theme" => KEYWORD,
						"other" => KEYWORD),
				
				// Data attributes 
				array(
						"title" => TEXT,
						"text" => TEXT),
				
				// Wrapped attributes
				array("title", "end"),
				
				$predicateStr);
		
	}
}

?>