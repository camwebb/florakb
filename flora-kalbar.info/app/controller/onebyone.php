<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

class onebyone extends Controller {
	
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
		$this->imagezip = $this->loadModel('imagezip');
	}
	
	public function index(){

		return $this->loadView('onebyone');

	}
    
    public function person(){
        return $this->loadView('formPerson');
    }
}

?>
