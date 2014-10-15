<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class input_data extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){
       
		$data = $this->contentHelper->getData();
		// pr($data);
		return $this->loadView('home');

	}
    
	
}

?>
