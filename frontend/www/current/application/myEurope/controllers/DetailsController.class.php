<? 
class DetailsController extends AuthenticatedController {
	
	public $id;
	public $details;
	
	public function handleRequest() {
		
		
		
		parent::handleRequest();
		 
		$this->id = $_GET['id'];
		$this->text = "description vide";
		
		debug('details of '.$this->id);
		
		$req = new DetailRequestv2($this, $_GET['namespace'], $this->id);
		
		$this->details = $req->send();

		if (isset($this->details))
			$this->renderView("details");
		else
			$this->redirect("results");
		// @todo errors
		
		// Render the view
		
	}
}
?>