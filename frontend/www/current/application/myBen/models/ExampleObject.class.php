<?

class ExampleObject extends GenericDataBean {
	
	public $pred1;
	public $pred2;
	public $pred3;
	
	public $data1;
	public $data2;
	public $data3;

	public function __construct() {
		parent::__construct(	
				// Predicate attributes
				array(
						"pred1" => KEYWORD,
						"pred2" => GPS,
						"pred3" => DATE),
				
				// Data attributes 
				array(
						"data1" => TEXT,
						"data2" => TEXT, 
						"data3" => ENUM));
	}
}

?>