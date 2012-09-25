<?

/** 
 *  Categories in the tree of Institution publications
 */
class InstitutionCategory extends InstitutionTreeNode {
	
	// ---------------------------------------------------------------------
	// Static method
	// ---------------------------------------------------------------------
	
	/** 
	 * Static getter. 
	 * $id = "root" return empty root category.
	 **/
	public static function getByID($id) {
		if ($id == "root") {
			// Dummy empty "root" category. 
			$cat = new InstitutionCategory();
			$cat->id = "root";
			return $cat;
		} else {
			return 	MyConsolatoPublication::getByIDGeneric($id, "InstitutionCategory");
		}
	}
	
	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Cache of children */
	protected $_childCategories = null;
	protected $_childArticles = null;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/** Construct */
	public function __construct() {
		
		parent::__construct();
		
		// Namespace
		$this->NAMESPACE = "InstitutionCategory";
	}
	
	// ---------------------------------------------------------------------
	// Methods
	// ---------------------------------------------------------------------
	
	public function getChildCategories() {
		
		if ($this->_childCategories == null) {
			// Find all nodes with this article as parent
			$req = new InstitutionCategory();
			$req->parentID = $this->id;
			$this->_childCategories = $req->find();
		}	
		return $this->_childCategories;
	}
	
	public function getChildArticles() {
	
		if ($this->_childArticles == null) {
			// Find all nodes with this article as parent
			$req = new InstitutionArticle();
			$req->parentID = $this->id;
			$this->_childArticles = $req->find();
		}
		return $this->_childArticles;
	}
	
}
?>