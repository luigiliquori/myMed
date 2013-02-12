<?

/** Profile for an association */
class Annonce extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key: Annonce ID */
	//public $id;
	public $type = "annonce";
	public $pred1;
	public $pred2;
	
	public $competences;
	public $typeMission;
	public $quartier;
	
	public $promue;
	public $validated;
	
	/** Data */
	public $title;
	public $text;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	public function __construct($predicateStr = null){
		parent::__construct(
		
			array(
				"publisher" => KEYWORD,
				"type"	=> KEYWORD,
				//"id" => KEYWORD,
				"title" => KEYWORD,
				"pred1" => KEYWORD,
				"pred2" => GPS,
				"competences" => ENUM,
				"typeMission" => ENUM,
				"quartier" => ENUM),
		
			// Data attributes
			array("text" => TEXT, "validated" => TEXT),
				
			// Wrapped attributes
			array("promue", "begin", "end"),
			
			$predicateStr);
		
	}
	
	// ---------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------
	
	/** Retrieve all candidatures */
	public function getCandidatures() {
		
		// Build a query to get them all 
		$candidatureQuery = new Candidature();
		$candidatureQuery->annonceID = $this->id;
		
		// Find all 
		$candidatures = $candidatureQuery->find();
		
		return $candidatures;
	}
	
	/** Retrieve all candidatures */
	public function getAssociation() {
		return ExtendedProfileRequired::getExtendedProfile($this->associationID);
		
	}
	
	/** Is this event passed ? */
	public function isPassed() {
		
		if (empty($this->end)) return false;
		
		// End date
		$date = DateTime::createFromFormat(DATE_FORMAT, $this->end);
		$today = new DateTime('today');
		
		return $today > $date;
	}
	
	// ---------------------------------------------------------------------
	// Overriden methods
	// ---------------------------------------------------------------------
	
	/** Promue is false by default */
/*	public function publish() {
		// No "promue" ? => false by default
		if ($this->promue == null) {
			$this->promue = "false";
		}
		if ($this->validated == null) {
			$this->validated = "false";
		}
		
		parent::publish();
	}
	*/
	
}
?>