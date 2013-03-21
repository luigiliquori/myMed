<?

/** Profile for an association */
class Annonce extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key: Annonce ID */
	public $id;
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
			// predicatesDef
			array(
				"publisher" => KEYWORD,
				"type"	=> KEYWORD,
				"id" => KEYWORD,
				"title" => KEYWORD,
				"pred1" => KEYWORD,
				"pred2" => GPS,
				"competences" => ENUM,
				"typeMission" => ENUM,
				"quartier" => ENUM),
		
			// dataDef
			array("text" => TEXT, "validated" => TEXT),
				
			// wrapDef
			array("promue", "begin", "end"),
			
			$predicateStr);
		
	}
}
?>
