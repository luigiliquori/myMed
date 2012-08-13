<?

/** 
 * Publications of type "Institution".
 * This is a basic CMS object. 
 */
class InstitutionArticle extends InstitutionCategory {
	
	// ---------------------------------------------------------------------
	// Static method
	// ---------------------------------------------------------------------
	
	/** Static getter */
	public static function getByID($id) {
		return MyConsolatoPublication::getByIDGeneric($id, "InstitutionArticle");
	}
	
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Full content */
	public $content;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Construct */
	public function __construct() {
		
		parent::__construct();
	
		// Namespace
		$this->NAMESPACE = "InstitutionArticle";
		
		// Data attributes
		$this->_dataDef['content'] = TEXT;
		
	}

}
?>