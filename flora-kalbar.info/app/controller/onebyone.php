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
	}
	
	public function index(){
		return $this->loadView('onebyone');
	}
    
    public function location(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formLocation');
    }
    
    public function person(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formPerson');
    }
    
    public function taxon(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formTaxon');
    }
    
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
}

?>
