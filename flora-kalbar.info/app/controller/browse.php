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
            $img = $this->browseHelper->showImgIndiv($getIndiv['result'][$i]['indivID'],true,'0,5');
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
        //get all images from indiv selected
        $indivImages = $this->browseHelper->showImgIndiv($indivID,false,'s');
        
        if(empty($indivDetail)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        $this->view->assign('indiv',$indivDetail);
        $this->view->assign('det',$indivDeterminant);
        $this->view->assign('img',$indivImages);
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
        //Get list location
        $listlocn = $this->insertonebyone->list_locn();
        $this->view->assign('locn', $listlocn);
        
        //get list person
        $listPerson = $this->insertonebyone->list_person();
        $this->view->assign('person', $listPerson);
        
        //get list taxon
        $listTaxon = $this->insertonebyone->list_taxon();
        $this->view->assign('taxon', $listTaxon);
        
        //get list enum confid
        $confid_enum = $this->insertonebyone->get_enum('det','confid');
        $this->view->assign('confid_enum', $confid_enum);
        
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
     * @todo insert location from edit Indiv
     * */
    public function insertLocation(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('locn',$data);
        
        if($insertData){
            $this->msg->add('s', 'Location Success Added');
        }else{
            $this->msg->add('e', 'Location Failed Added');
        }
        header('Location: ../../browse/editIndiv/?id='.$_GET['id']);
    }
    
    public function addDetView(){
        //get list taxon
        $listTaxon = $this->insertonebyone->list_taxon();
        $this->view->assign('taxon', $listTaxon);
        
        //get list enum confid
        $confid_enum = $this->insertonebyone->get_enum('det','confid');
        $this->view->assign('confid_enum', $confid_enum);
        return $this->loadView('addDetView');
    }
    
    /**
     * @todo insert individu from posted data
     * */
    public function addDet(){
        $data = $_POST;
        $ses_user = $this->isUserOnline(); 
        $personID = $ses_user['login']['id'];
        
        $data['personID'] = $personID;       
        $data['indivID'] = $_GET['id'];
        $data['det_date'] = date("Y-m-d");
        
        $insertData = $this->insertonebyone->insertTransaction('det',$data);
        
        if($insertData){
            $this->msg->add('s', 'Determinant Success Added');
        }else{
            $this->msg->add('e', 'Determinant Failed Added');
        }
        
        if($_GET['action']=='addOnly'){
            header('Location: ../../browse/indivDetail/?id='.$data['indivID']);
        }
        else{
            header('Location: ../../browse/editIndiv/?id='.$data['indivID']);
        }
    }
    
    /**
     * @todo insert taxon from posted data
     * */
    public function insertTaxon(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('taxon',$data);
        
        if($insertData){
            $this->msg->add('s', 'Taxon Success Added');
        }else{
            $this->msg->add('e', 'Taxon Failed Added');
        }
        header('Location: ../../browse/editIndiv/?id='.$_GET['id']);
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
