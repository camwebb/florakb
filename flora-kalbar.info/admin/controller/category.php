<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class category extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->models = $this->loadModel('mcategory');
	}
	
	public function index(){
		$_SESSION['pages'] = 'kategori';
		// $data = $this->contentHelper->getData();
		// pr($data);
		return $this->loadView('viewcategory');

	}
    
	
}

?>
