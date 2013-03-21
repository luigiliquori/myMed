<?

/** 
 *  A node in the tree of categories/articles
 */
abstract class InstitutionTreeNode extends MyConsolatoPublication {
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Parent node */
	public $parentID;
	
	/** Short title */
	public $title;
	
	/** Short description (wrapped) */
	public $desc;

	/** Cache of parent */
	protected $_parent;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Construct */
	public function __construct() {
		
		parent::__construct();
			
		// Predicates
		$this->_predicatesDef["parentID"] = KEYWORD; 
		
		// Wrapped
		$this->_wrapDef[] = "title";
		$this->_wrapDef[] = "desc";
	}
	
	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	public function getParent() {
		if ($this->_parent == null) {
			$this->_parent = InstitutionCategory::getByID($this->parentID);
		}
		return $this->_parent;
	}
	
}
?>