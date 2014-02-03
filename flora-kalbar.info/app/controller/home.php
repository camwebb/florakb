<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class home extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		$this->loadmodule();
		// pr($this->view);
		// exit;
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		// $this->models = $this->loadModel('frontend');
	}
	
	public function index(){
        
		$var = array(1,2,3);
		
		
		vd($this);
		// $this->view->assign('test',$var);
		
		$var = 'masuk';
		return $this->loadView('home');

	}
        
	public function tangkap(){
		if(isset($_POST)){
			// validasi value yang masuk
		   $x = form_validation($_POST);
		   
		   $data['input'] = $this->models->inputData($x['id'],$x['nama'],$x['alamat']);
		   
		   /* tampung kembalian data dari fungsi yang dipanggil */
			//$data['frontend'] = $this->models->get_data_desc();
		   if($data['input'])
		   {
			   //return $this->loadView('display');
			   global $CONFIG;
			   redirect($CONFIG['default']['base_url']."display");
		   }else {
			   echo "gagal";
		   }
		   
		   //pr($x);
		}
	}
	
}

?>
