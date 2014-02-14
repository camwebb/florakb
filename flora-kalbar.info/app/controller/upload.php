<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class upload extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
	}
	public function loadmodule()
	{
		
		// $this->models = $this->loadModel('frontend');
	}
	
	public function index(){
        
		$var = array(1,2,3);

		$this->view->assign('test',$var);

		return $this->loadView('upload');

	}
    
    function zip(){
        //uploadFile --> engine function
        $name = 'imagezip';
        $path = '';
        $test = uploadFile($name, $path);
        pr($test);
    }
	
}

?>
