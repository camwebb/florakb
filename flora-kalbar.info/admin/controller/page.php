<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class page extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->models = $this->loadModel('mpage');
	}
	
	public function profile($page=null,$status=null){
		
		if(!$page){
			$category = $_GET['page'];
		} else {
			$category = $page;
		}
		if($category=="tentang-pnj"){
			$data['title'] = "Tentang PNJ";
			$data['catid'] = "11";
		} elseif($category=="tentang-cdc"){
			$data['title'] = "Tentang CDC";
			$data['catid'] = "12";
		} elseif($category=="visi-misi"){
			$data['title'] = "Visi dan Misi";
			$data['catid'] = "13";
		} elseif($category=="staff"){
			$data['title'] = "Staff";
			$data['catid'] = "14";
		} elseif($category=="sejarah"){
			$data['title'] = "Sejarah";
			$data['catid'] = "15";
		} elseif($category=="kontak"){
			$data['title'] = "Kontak";
			$data['catid'] = "16";
		} elseif($category=="jobseeker"){
			$data['title'] = "Job Seeker";
			$data['catid'] = "17";
		}elseif($category=="corporate"){
			$data['title'] = "Corporate";
			$data['catid'] = "18";
		} elseif($category=="advertise"){
			$data['title'] = "Advertise";
			$data['catid'] = "19";
		}
		
		if($status) $data['status'] = 1;
		
		$data['url'] = $category;
		
		$data['item'] = $this->models->get_page_id($data['catid']);
		
		$_SESSION['pages'] = 'page';
		return $this->loadView('inputprofile',$data);

	}
	
	public function profileinp(){
		global $CONFIG;

		if(!empty($_POST)){
		
			if(isset($_POST['status'])){
				if($_POST['status']=='on') $_POST['status']=1;
			} else {
				$_POST['status']=0;
			}
			if(isset($_POST['articletype'])){
				if($_POST['articletype']=='on') $_POST['articletype']=1; 
			} else {
				$_POST['articletype']=0;
			}
			
			if(isset($_POST)){
				$x = form_validation($_POST);

				   try
				   {
						if(isset($x) && count($x) != 0)
						{
							//update or insert
							$x['action'] = 'insert';
							if($x['id'] != ''){
								$x['action'] = 'update';
							}
							
							//upload file
							if(!empty($_FILES)){
								if($_FILES['file_image']['name'] != ''){
									if($x['action'] == 'update') deleteFile($x['image']);
									$image = uploadFile('file_image');
									$x['image'] = $image['filename'];
								}
							}
							$data = $this->models->page_inp($x);
						}
				}catch (Exception $e){}
				
				return $this->profile($x['page'],1);
			}
		} else {
			echo "<script>window.location.href='".$CONFIG['admin']['base_url']."page/profile/?page=tentang-pnj'</script>";
		}
	}
	
	public function get_ads(){
		$data['item'] = $this->models->get_ads_list();
		
		$data['ads_type'] = $this->models->get_ads_type_all();
		
		
		$_SESSION['pages'] = 'ads';
		return $this->loadView('viewads',$data);
	}
	
	public function addads(){
		
		$data['ads_type'] = $this->models->get_ads_type(6);
		// pr($data);
		return $this->loadView('inputads',$data);
	}
	
	public function inputads(){
		global $CONFIG;
		if(!empty($_POST)){
		
			if(isset($_POST['n_status'])){
				if($_POST['n_status']=='on') $_POST['n_status']=1;
			} else {
				$_POST['n_status']=0;
			}
			// if(isset($_POST['articletype'])){
				// if($_POST['articletype']=='on') $_POST['articletype']=1; 
			// } else {
				// $_POST['articletype']=0;
			// }
			
			
			if(isset($_POST)){
				$x = form_validation($_POST);

				   try
				   {
						if(isset($x) && count($x) != 0)
						{
							//update or insert
							$x['action'] = 'insert';
							if($x['id'] != ''){
								$x['action'] = 'update';
							}
							
							//set status existing ads
							// $status_ads = $this->models->upd_type($x['articletype'],$x['articletype_old']);
							
							//upload file
							if(!empty($_FILES)){
								if($_FILES['file_image']['name'] != ''){
									if($x['action'] == 'update') deleteFile($x['image']);
									$image = uploadFile('file_image');
									$x['image'] = $image['filename'];
								}
							}

							$data = $this->models->ads_inp($x);
						}
				}catch (Exception $e){}
				
				echo "<script>window.location.href='".$CONFIG['admin']['base_url']."page/get_ads'</script>";
			}
		}
	}
	
	public function ads_del(){
		global $CONFIG;
		if(isset($_GET['id'])) $id = $_GET['id'];
	
		$data = $this->models->del_ads($id);
		
		deleteFile($data[0]['image']);
		
		echo "<script>window.location.href='".$CONFIG['admin']['base_url']."page/get_ads'</script>";
	}
	
	public function ads_upd(){
		if(isset($_GET['id'])) $id = $_GET['id'];
		
		$data['item'] = $this->models->get_ads_id($id);
		// pr($data['item']);
		$data['ads_type'] = $this->models->get_ads_type(6);
		// pr($data['ads_type']);
		return $this->loadView('inputads',$data);
	}
}

?>
