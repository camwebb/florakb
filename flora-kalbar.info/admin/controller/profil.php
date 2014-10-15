<?php
defined ('TATARUANG') or exit ( 'Forbidden Access' );

class profil extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		$this->models = $this->loadModel('m_profil');
		// $this->contentHelper = $this->loadModel('m_profil');
	}
	
	//view index(sejarah)
	public function index(){
       
		$data['new-button'] = 'berita-new';
		$data['menu'] = $this->loadView('profil/profile-sejarah-menu');
		$table="dtataruang_news_content";
		$categoryid="1";
		$articletype="1";
		$data['result']= $this->models->viewData_sejarah($table,$categoryid,$articletype);
		if($data){
			return $this->loadView('profil/profile-sejarah-input',$data);
		}else{
			return $this->loadView('profil/profile-sejarah-input',$data);
		}		
	}
	
    //view sejarah
	function sejarah()
	{
		$data['new-button'] = 'berita-new';
		$data['menu'] = $this->loadView('profil/profile-sejarah-menu');
		$table="dtataruang_news_content";
		$categoryid="1";
		$articletype="1";
		$data['result'] = $this->models->viewData_sejarah($table,$categoryid,$articletype);
		
		if($result){
			return $this->loadView('profil/profile-sejarah-input',$data);
		}else{
			return $this->loadView('profil/profile-sejarah-input',$data);
		}		
		
	}
	//insert or edit sejarah
	public function input_sejarah(){
		if(isset($_POST)){
			$x = form_validation($_POST);	
			try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						if (!empty($_FILES['img_strtr']['name']) ) {
							$files = uploadFile('img_strtr','sejarah'); 
							$data["img_strtr"]="sejarah/".$files['filename']; 
						}
						else{
							$data["img_strtr"]=$_POST['img_hidden'];
						}
						$input_data_peraturan = $this->models->inputData_sejarah($x['id'],$x['title'],$x['content'],$data["img_strtr"],$x['categoryid'],$x['articletype'],
																					$x['createdate'],$x['postdate'],$x['fromwho'],$x['authorid'],$x['n_status'],$x['img_hidden']);
					}
				}catch (Exception $e){}
		}
		echo "<script>alert('Berhasil');document.location='index';</script>";
	}
	
	//view struktur organisasi 
	function struktur()
	{
		$data['menu'] = $this->loadView('profil/profile-sejarah-menu');
		$table="dtataruang_news_content";
		$categoryid="2";
		$articletype="1";
		$data['result'] = $this->models->viewData_struktur($table,$categoryid,$articletype);
		
		if($result){
			return $this->loadView('profil/profile-strukur-organisasi-input',$data);
		}else{
			return $this->loadView('profil/profile-strukur-organisasi-input',$data);
		}
	}
	
	//insert or edit struktur organisasi
	public function input_struktur(){
		if(isset($_POST)){
			$x = form_validation($_POST);	
			try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						if (!empty($_FILES['img_strtr']['name']) ) {
							$files = uploadFile('img_strtr','struktur organisasi'); 
							$data["img_strtr"]="struktur organisasi/".$files['filename']; 
						}else{
							$data["img_strtr"]=$_POST['img_hidden'];
						}
						$input_data_peraturan = $this->models->inputData_struktur($x['id'],$x['title'],$x['content'],$data["img_strtr"],$x['categoryid'],$x['articletype'],
																					$x['createdate'],$x['postdate'],$x['fromwho'],$x['authorid'],$x['n_status'],$x['img_hidden']);
					}
				}catch (Exception $e){}
		}
		echo "<script>alert('Berhasil');document.location='struktur';</script>";
	}
	
	//view tupoksi
	function tupoksi()
	{
		$data['menu'] = $this->loadView('profil/profile-sejarah-menu');
		$table="dtataruang_news_content";
		$categoryid="3";
		$articletype="1";
		$data['result'] = $this->models->viewData_tupoksi($table,$categoryid,$articletype);
		
		if($result){
			return $this->loadView('profil/profile-tupoksi-input',$data);
		}else{
			return $this->loadView('profil/profile-tupoksi-input',$data);
		}
		
	}
	
	//insert or edit struktur organisasi
	public function input_tupoksi(){
		if(isset($_POST)){
			$x = form_validation($_POST);	
			try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						if (!empty($_FILES['img_strtr']['name']) ) {
							$files = uploadFile('img_strtr','tupoksi'); 
							$data["img_strtr"]="tupoksi/".$files['filename']; 
						}else{
							$data["img_strtr"]=$_POST['img_hidden'];
						}
						
						$input_data_peraturan = $this->models->inputData_tupoksi($x['id'],$x['title'],$x['content'],$data["img_strtr"],$x['categoryid'],$x['articletype'],
																					$x['createdate'],$x['postdate'],$x['fromwho'],$x['authorid'],$x['n_status'],$x['img_hidden']);
					}
				}catch (Exception $e){}
		}
		echo "<script>alert('Berhasil');document.location='tupoksi';</script>";
	}
	
	
	
}

?>
