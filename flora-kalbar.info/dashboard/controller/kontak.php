<?php
defined ('TATARUANG') or exit ( 'Forbidden Access' );

class kontak extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		 $this->models = $this->loadModel('m_kontak');
	}
	
	public function index(){
       
		$data['isi']= $this->models->getData_kontak();
	   
		$data['menu'] = $this->loadView('berita/informasi-berita-menu');
		
		return $this->loadView('kontak',$data);

	}
    
	function inputData() 
	{
		if (isset($_POST)){
			$x = form_validation($_POST);
			
			try {
			
				if (isset($x) && count($x) !=0) {
				
				$uploadImage['status'] = false;
				if ($_FILES['image']['name']!="")$uploadImage = uploadFile('image','kontak');
				
				$input_data_kontak = $this->models->inputData_kontak($x['content']);
				
				
					if ($uploadImage['status']){
					
					$updateUser = $this->models->updateUserImage($uploadImage['filename'],$input_data_kontak);
					
					}
				}
			} catch (Exception $e) {}
		}
		echo "<script>alert('Berhasil');document.location='index';</script>";
	}
	
	
			
		
			
		
	
	
	
}

?>
