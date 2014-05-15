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
        $taxon = $this->browseHelper->dataTaxon(false,'','');
        
        for($i=0;$i<count($taxon);$i++){
            //Get taxon's 'images
            $img = $this->browseHelper->showImgTaxon($taxon[$i]['id']);
            $listAll[]= array('taxon'=>$taxon[$i],'img'=>$img);
        }   
        $this->view->assign('data',$listAll);
        return $this->loadView('browse');
    }
    
    /**
     * @todo show all indiv from selected taxon
     * 
     */
    function indiv(){
        $taxonID = $_GET['id'];
        //get taxon name
        $taxonName = $this->browseHelper->dataTaxon(true,'id',$taxonID);
        
        //get data indiv
        $getIndiv = $this->browseHelper->dataIndiv($taxonID);
        
        $listAll = array();
        for($i=0;$i<count($getIndiv);$i++){
            //Get indiv's 'images
            $img = $this->browseHelper->showImgIndiv($getIndiv[$i]['indivID']);
            $listAll[]= array('indiv'=>$getIndiv[$i],'img'=>$img);
        }
        
        $this->view->assign('taxonName',$taxonName);
        $this->view->assign('data',$listAll);
        return $this->loadView('browseIndiv');
    }
    
    /**
     * @todo show all detail indiv from selected indiv
     * 
     */
    function indivDetail(){
        $indivID = $_GET['id'];
        //get whole data indiv detail
        $indivDetail = $this->browseHelper->detailIndiv($indivID);
        //get determinant from selected indiv
        $indivDeterminant = $this->browseHelper->dataDetIndiv($indivID);
        
        $this->view->assign('indiv',$indivDetail);
        $this->view->assign('det',$indivDeterminant);
        return $this->loadView('browseIndivDetail');
    }
	
}

?>
