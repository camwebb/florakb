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
        
        $listAll = array();
        
        //Get all data taxon
        $taxon = $this->browseHelper->dataTaxon();
        
        for($i=0;$i<count($taxon);$i++){
            //Get taxon's 'images
            $img = $this->browseHelper->showImg($taxon[$i]['id']);
            $listAll[]= array('taxon'=>$taxon[$i],'img'=>$img);
        }   
        $this->view->assign('data',$listAll);
        return $this->loadView('browse');
    }
	
}

?>
