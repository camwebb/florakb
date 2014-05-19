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
        $this->msg = new Messages();
    }
	
	function loadmodule()
	{
        $this->insertonebyone = $this->loadModel('insertonebyone');
        $this->browseHelper = $this->loadModel('browseHelper');
	}
	
	function index(){
        
        
    }
    
    function data(){
        
        $listAll = array();
        
        //Get all data taxon
        $taxon = $this->browseHelper->dataTaxon(false,'','');
        
        for($i=0;$i<count($taxon['result']);$i++){
            //Get taxon's 'images
            $img = $this->browseHelper->showImgTaxon($taxon['result'][$i]['id']);
            $listAll[]= array('taxon'=>$taxon['result'][$i],'img'=>$img);
        }   
        
        if(empty($listAll)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        
        $this->view->assign('pageno',$taxon['pageno']);
        $this->view->assign('lastpage',$taxon['lastpage']);
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
        for($i=0;$i<count($getIndiv['result']);$i++){
            //Get indiv's 'images
            $img = $this->browseHelper->showImgIndiv($getIndiv['result'][$i]['indivID']);
            $listAll[]= array('indiv'=>$getIndiv['result'][$i],'img'=>$img);
        }
        
        if(empty($listAll)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        $this->view->assign('pageno',$getIndiv['pageno']);
        $this->view->assign('lastpage',$getIndiv['lastpage']);        
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
        
        if(empty($indivDetail)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        $this->view->assign('indiv',$indivDetail);
        $this->view->assign('det',$indivDeterminant);
        $ses_user = $this->isUserOnline();
        $this->view->assign('user', $ses_user); 
        return $this->loadView('browseIndivDetail');
    }
    
    /**
     * @todo show all detail indiv from selected indiv
     * 
     */
    function editIndiv(){
        //get data user from session
        $ses_user = $this->isUserOnline();
        global $basedomain;
        if(!$ses_user){
            header('Location: '.$basedomain);
        }
        $indivID = $_GET['id'];
        //get whole data indiv detail
        $indivDetail = $this->browseHelper->detailIndiv($indivID);
        //get determinant from selected indiv
        $indivDeterminant = $this->browseHelper->dataDetIndiv($indivID);
        
        if(empty($indivDetail)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        
        $listlocn = $this->insertonebyone->list_locn();
        $this->view->assign('locn', $listlocn);
        
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        $this->view->assign('indiv',$indivDetail);
        $this->view->assign('det',$indivDeterminant);
        return $this->loadView('editIndiv');
    }
    
    
    /**
     * @todo edit Indiv proccess
     * 
     */
    function doEditIndiv(){
        $data = $_POST;
        
        //get data user from session
        $ses_user = $this->isUserOnline();
        
        $idIndiv = $_GET['id'];
        $personID = $ses_user['login']['id'];
        $data['personID'] = $personID;
        
        //pr($idIndiv);exit;
        $updateData = $this->browseHelper->updateIndiv($data,$idIndiv);
        
        if($updateData){
            $this->msg->add('s', 'Update Individu Success');
        }else{
            $this->msg->add('e', 'Update Individu Failed');
        }
        
        header('Location: ../../browse/editIndiv/?id='.$idIndiv);
    }
    
    /**
     * @todo search from table taxon
     * 
     */
    function search(){
        $data=$_GET['search'];
        
        $search=$this->browseHelper->searchTaxon($data);
        
        if(empty($search)){
            $this->view->assign('noData','empty');
        }
        else{
            $totalSearch = count($search);
            $this->view->assign('noData',$totalSearch);
        }
        $this->view->assign('data',$search);
        return $this->loadView('browseSearchResult');       
    }
	
}

?>
