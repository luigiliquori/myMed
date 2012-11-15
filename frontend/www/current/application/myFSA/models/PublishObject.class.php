<?

/** This class is used to storing a publication of myFSA */
class PublishObject extends GenericDataBean {
	
	/** Some predicates */
	public $pred1;
	public $pred2;
	public $pred3;
	public $date;
	
	/**
	 * Some fields, not part of predicates, but wrapped in "_data"
	 * to be fetched by a "search" query (no need to "details").
	 * Register them in the contructor (3rd array)
	 */
	public $wrapped1;
	public $wrapped2;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $data1;
	public $data2;
	public $data3;
	
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
		array(
			"data1" => TEXT,
			"data2" => TEXT,
			"data3" => TEXT),
	
		// Wrapped attributes
		array("wrapped1", "wrapped2"),
	
		$predicateStr);
	}
}

?>