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
        return $this->loadView('formLocation');
    }
    
    public function person(){
        $this->msg->add('s', 'This is a sample Success Message');
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        return $this->loadView('formPerson');
    }
    
    public function taxon(){
        return $this->loadView('formTaxon');
    }
    
    public function insertPerson(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('person',$data);
        pr($insertData);
        exit;
    }
}

?>
