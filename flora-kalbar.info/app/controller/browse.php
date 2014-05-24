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
    
    /**
     * @todo show all taxon
     * 
     */
    function dataTaxon(){
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
        return $this->loadView('browseTaxon');
    }
    
    /**
     * @todo show all location
     * 
     */
    function dataLocation(){
        $listAll = array();
        
        //Get all data location
        $location = $this->browseHelper->dataLocation(false,'','');
        
        if(empty($location)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        
        $this->view->assign('pageno',$location['pageno']);
        $this->view->assign('lastpage',$location['lastpage']);
        $this->view->assign('result',$location['result']);
        return $this->loadView('browseLocation');
    }
    
    /**
     * @todo show all person
     * 
     */
    function dataPerson(){
        $listAll = array();
        
        //Get all data person
        $person = $this->browseHelper->dataPerson(false,'','');
        
        if(empty($person)){
            $this->view->assign('noData','empty');
        }
        else{
            $this->view->assign('noData','data existed');
        }
        
        $this->view->assign('pageno',$person['pageno']);
        $this->view->assign('lastpage',$person['lastpage']);
        $this->view->assign('result',$person['result']);
        return $this->loadView('browsePerson');
    }
    
    /**
     * @todo show all indiv from selected taxon/location/person
     * 
     */
    function indiv(){
        $id = $_GET['id'];
        $action = $_GET['action'];
        
        if($action=='indivTaxon'){
            //get taxon name
            $title = $this->browseHelper->dataTaxon(true,'id',$id);
            //get data indiv
            $getIndiv = $this->browseHelper->dataIndiv($action,'taxonID',$id);
        }
        if($action=='indivLocn'){
            $title='';
            //get data indiv
            $getIndiv = $this->browseHelper->dataIndiv($action,'locnID',$id);
        }
        if($action=='indivPerson'){
            $title='';
            //get data indiv
            $getIndiv = $this->browseHelper->dataIndiv($action,'personID',$id);
        }
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
        $this->view->assign('title',$title);
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
        $indivImages = $this->browseHelper->showImgIndiv($indivID,false,'');
        //get all observations from indiv selected
        $indivObs = $this->browseHelper->dataObsIndiv($indivID);
        
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
        $this->view->assign('obs',$indivObs);
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
        //get all observations from indiv selected
        $indivObs = $this->browseHelper->dataObsIndiv($indivID);
        
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
        
        //get list enum habit
        $habit_enum = $this->insertonebyone->get_enum('obs','habit');
        $this->view->assign('habit_enum', $habit_enum);
        
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        $this->view->assign('obs',$indivObs);
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
     * @todo update all indiv selected n_status into '1'
     * 
     */
    function deleteIndiv(){
        $idIndiv = $_GET['id'];
        $data['indivID'] = $idIndiv;
        $deleteIndiv = $this->browseHelper->deleteIndiv('','indiv','id',$data);
        $deleteColl = $this->browseHelper->deleteIndiv('','coll','indivID',$data);
        $deleteObs = $this->browseHelper->deleteIndiv('','obs','indivID',$data);
        $deleteImg = $this->browseHelper->deleteIndiv('','img','indivID',$data);
        $deleteDet = $this->browseHelper->deleteIndiv('','det','indivID',$data);
        
        if($deleteIndiv && $deleteColl && $deleteObs && $deleteImg && $deleteDet){
            $this->msg->add('s', 'Delete Individu Success');
        }else{
            $this->msg->add('e', 'Delete Individu Failed');
        }
        
        header('Location: ../../browse/indivDetail/?id='.$idIndiv);
    }
    
    /**
     * @todo update all det selected n_status into '1'
     * 
     */
    function deleteDet(){
        $idIndiv = $_GET['indivID'];
        $idDet = $_GET['id'];
        $data['indivID'] = $idIndiv;
        $data['id'] = $idDet;
        $deleteDet = $this->browseHelper->deleteIndiv('AND','det','indivID',$data);
        
        if($deleteDet){
            $this->msg->add('s', 'Delete Determinant Success');
        }else{
            $this->msg->add('e', 'Delete Determinant Failed');
        }
        
        header('Location: ../../browse/indivDetail/?id='.$idIndiv);
    }
    
    /**
     * @todo update all det selected n_status into '1'
     * 
     */
    function deleteObs(){
        $idIndiv = $_GET['indivID'];
        $idObs = $_GET['id'];
        $data['indivID'] = $idIndiv;
        $data['id'] = $idObs;
        $deleteDet = $this->browseHelper->deleteIndiv('AND','obs','indivID',$data);
        
        if($deleteDet){
            $this->msg->add('s', 'Delete Observation Success');
        }else{
            $this->msg->add('e', 'Delete Observation Failed');
        }
        
        header('Location: ../../browse/indivDetail/?id='.$idIndiv);
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
        //get list person
        $listPerson = $this->insertonebyone->list_person();
        $this->view->assign('person', $listPerson);
        
        //get list taxon
        $listTaxon = $this->insertonebyone->list_taxon();
        $this->view->assign('taxon', $listTaxon);
        
        //get list enum confid
        $confid_enum = $this->insertonebyone->get_enum('det','confid');
        $this->view->assign('confid_enum', $confid_enum);
        return $this->loadView('addDetView');
    }
    
    public function addObsView(){
        //get list person
        $listPerson = $this->insertonebyone->list_person();
        $this->view->assign('person', $listPerson);
        
        //get list taxon
        $listTaxon = $this->insertonebyone->list_taxon();
        $this->view->assign('taxon', $listTaxon);
        
        //get list enum confid
        $habit_enum = $this->insertonebyone->get_enum('obs','habit');
        $this->view->assign('habit_enum', $habit_enum);
        
        //get list enum subtype
        $subtype_enum = $this->insertonebyone->get_enum('taxon','subtype');
        $this->view->assign('subtype_enum', $subtype_enum);
        
        return $this->loadView('addObsView');
    }
    
    /**
     * @todo insert determinant from posted data
     * */
    public function addDet(){
        $data = $_POST;
        
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
     * @todo insert observation from posted data
     * */
    public function addObs(){
        $data = $_POST;
        $ses_user = $this->isUserOnline(); 
        $personID = $ses_user['login']['id'];
        
        $data['personID'] = $personID;       
        $data['indivID'] = $_GET['id'];
        
        $insertData = $this->insertonebyone->insertTransaction('obs',$data);
        
        if($insertData){
            $this->msg->add('s', 'Observation Success Added');
        }else{
            $this->msg->add('e', 'Observation Failed Added');
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
    function searchTaxon(){
        $data=$_GET['search'];
        
        $search=$this->browseHelper->search('taxon',$data);
        
        if(empty($search)){
            $this->view->assign('noData','empty');
        }
        else{
            $totalSearch = count($search['countdata']);
            $this->view->assign('noData',$totalSearch);
        }
        $this->view->assign('pageno',$search['pageno']);
        $this->view->assign('lastpage',$search['lastpage']);
        $this->view->assign('data',$search['result']);
        return $this->loadView('browseSearchTaxon');       
    }
    
    /**
     * @todo search from table taxon
     * 
     */
    function searchLocn(){
        $data=$_GET['search'];
        
        $search=$this->browseHelper->search('locn',$data);
        
        if(empty($search)){
            $this->view->assign('noData','empty');
        }
        else{
            $totalSearch = count($search['countdata']);
            $this->view->assign('noData',$totalSearch);
        }
        $this->view->assign('pageno',$search['pageno']);
        $this->view->assign('lastpage',$search['lastpage']);
        $this->view->assign('data',$search['result']);
        return $this->loadView('browseSearchLocn');       
    }
    
    /**
     * @todo search from table taxon
     * 
     */
    function searchPerson(){
        $data=$_GET['search'];
        
        $search=$this->browseHelper->search('person',$data);
        
        if(empty($search)){
            $this->view->assign('noData','empty');
        }
        else{
            $totalSearch = count($search['countdata']);
            $this->view->assign('noData',$totalSearch);
        }
        $this->view->assign('pageno',$search['pageno']);
        $this->view->assign('lastpage',$search['lastpage']);
        $this->view->assign('data',$search['result']);
        return $this->loadView('browseSearchPerson');       
    }
	
}

?>
