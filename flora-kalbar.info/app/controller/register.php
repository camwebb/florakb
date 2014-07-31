<?php

class register extends Controller {
	
	var $models = FALSE;
	var $view;

	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
        $this->msg = new Messages();                
    }
	
	function loadmodule()
	{
        //used for check name, twitter, and email only
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->activityHelper = $this->loadModel('activityHelper');
        
	}
	
	function index(){
        global $DATA;

        // pr($DATA);
    	return $this->loadView('register');
    }
    
    

}

?>
