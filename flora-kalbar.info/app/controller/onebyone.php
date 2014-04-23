<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

//------------------------------------------------------------------------------
// A session is required for the messages to work
//------------------------------------------------------------------------------
if( !session_id() ) session_start();

class onebyone extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
        $this->msg = new Messages();
	}
	public function loadmodule()
	{
        $this->insertonebyone = $this->loadModel('insertonebyone');
        
        //only used for check name, twitter, and email
        $this->loginHelper = $this->loadModel('loginHelper');
	}
	
	public function index(){
		return $this->loadView('onebyone');
	}
    
    /**
     * @todo show view for location form
     * */
    public function location(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formLocation');
    }
    
    /**
     * @todo show view for person form
     * */
    public function person(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formPerson');
    }
    
    /**
     * @todo show view for taxon form
     * */
    public function taxon(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formTaxon');
    }
    
    /**
     * @todo insert person from posted data
     * */
    public function insertPerson(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('person',$data);
        
        if($insertData){
            $this->msg->add('s', 'This is a sample Success Message');
        }else{
            $this->msg->add('e', 'This is a sample Error Message');
        }
        header('Location: ../onebyone/person');
    }
    
    public function check_Name(){
        $data = $_POST['name'];
        $check = $this->loginHelper->checkName($data);
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
    
    public function check_Twitter(){
        $data = $_POST['twitter'];
        $check = $this->loginHelper->checkTwitter($data);
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
    
    public function check_Email(){
        $return = false;
    
        $data = $_POST['email'];
        $check = $this->loginHelper->checkEmail($data);
        
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
}

?>
