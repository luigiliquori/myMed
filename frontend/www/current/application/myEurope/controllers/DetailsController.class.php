<? 
class DetailsController extends AuthenticatedController {
	
	public $id;
	public $details;
	
	public function handleRequest() {
		
		
		
		parent::handleRequest();
		
		if (isset($_GET["rm"])){
			$this->delData();
		}
		 
		$this->id = $_GET['id'];
		$this->text = "description vide";
		
		debug('details of '.$this->id);
		
		$req = new DetailRequestv2($this, $_GET['namespace'], $this->id);
		
		try{
			$this->details = $req->send();
		}
		catch(Exception $e){
			//return null;
			$this->details=new StdClass();
			$this->details->text = "Contenu effacé par l'auteur";
		}

		if (isset($this->details))
			$this->renderView("details");
		else
			$this->redirect("search");
		// @todo errors
		
		// Render the view
		
	}
	
	
	public /*void*/ function delData(){
	
	
		$publish = new PublishRequestv2($this, $_GET['namespace'], $_GET['id']);
		
		debug('trying to delete '.$_GET['namespace']."..".$_GET['id']);
		
		$publish->setMethod(DELETE);
		$publish->send();
	
	
	}
}
?>