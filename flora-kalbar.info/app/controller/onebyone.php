<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

class onebyone extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
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
        return $this->loadView('formPerson');
    }
    
    public function taxon(){
        return $this->loadView('formTaxon');
    }
    
    public function insertPerson(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('person',$data);
        exit;
    }
}

?>
