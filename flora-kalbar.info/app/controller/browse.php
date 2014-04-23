<?php

class browse extends Controller {
	
	var $models = FALSE;
	var $view;

	
	function __construct()
	{
    	global $basedomain;
    	$this->loadmodule();
    	$this->view = $this->setSmarty();
    	$this->view->assign('basedomain',$basedomain);
    }
	
	function loadmodule()
	{
        //$this->models = $this->loadModel('frontend');
        $this->browseHelper = $this->loadModel('browseHelper');
	}
	
	function index(){
        $taxon = $this->browseHelper->dataTaxon(); 
        $this->view->assign('taxon',$taxon);
        return $this->loadView('browse');
    }
	
}

?>
