<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class article extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->models = $this->loadModel('marticle');
	}
	
	public function index(){
       
		$data = $this->models->get_article();
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
				$data[$key]['postdateChange'] = changeDate($val['postdate']);
			}
		}
		
		// pr($data);
		$_SESSION['pages'] = 'news';
		return $this->loadView('viewarticle',$data);

	}
	
	public function addarticle(){
		
		$data['category'] = $this->models->get_category();

		if(isset($_GET['id']))
		{
			$data['item'] = $this->models->get_article_id($_GET['id']);	
		} 
		
		return $this->loadView('inputarticle',$data);
	}
    
	public function articleinp(){
		global $CONFIG;
		
		if(isset($_POST['status'])){
			if($_POST['status']=='on') $_POST['status']=1;
		} else {
			$_POST['status']=0;
		}
		if(isset($_POST['articletype'])){
			if($_POST['articletype']=='on') {
				if($_POST['articleid_old']!=0){
					$_POST['articletype'] = $_POST['articleid_old'];
				} else {
					$_POST['articletype']=1; 
				}
			}
		} else {
			$_POST['articletype']=0;
		}
 
		if(isset($_POST)){
                // validasi value yang masuk
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
						$data = $this->models->article_inp($x);
			   		}
				   	
			   }catch (Exception $e){}
			   
            echo "<script>alert('Data berhasil di simpan');window.location.href='".$CONFIG['admin']['base_url']."article/index'</script>";
            }
	}
	
	public function articledel(){

		global $CONFIG;
		
		$id = $_GET['id'];

		$data = $this->models->article_del($id);
		
		echo "<script>alert('Data berhasil di hapus');window.location.href='".$CONFIG['admin']['base_url']."article/index'</script>";
		
	}
	
	public function trash(){
       
		$data = $this->models->get_article_trash(1);
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
				$data[$key]['postdateChange'] = changeDate($val['postdate']);
			}
		}
		
		// pr($data);
		$_SESSION['pages'] = 'trash';
		return $this->loadView('viewtrash',$data);

	}
	
	public function trashlowongan(){
       
		$data = $this->models->get_article_trash(2);
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
				$data[$key]['postdateChange'] = changeDate($val['postdate']);
			}
		}
		
		// pr($data);
		$_SESSION['pages'] = 'trash';
		return $this->loadView('viewtrash',$data);

	}
	
	public function trashads(){
       
		$data = $this->models->get_article_trash(6);
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
				$data[$key]['postdateChange'] = changeDate($val['postdate']);
			}
		}
		
		// pr($data);
		$_SESSION['pages'] = 'trash';
		return $this->loadView('viewtrash',$data);

	}
	
	public function articlerest(){

		global $CONFIG;
		
		$id = $_GET['id'];

		$data = $this->models->article_restore($id);
		
		echo "<script>alert('Data berhasil di restore');window.location.href='".$CONFIG['admin']['base_url']."article/trash'</script>";
		
	}
	
	public function articledelp(){

		global $CONFIG;
		
		$id = $_GET['id'];

		$data = $this->models->article_delpermanent($id);
		
		echo "<script>alert('Data berhasil di hapus secara permanen');window.location.href='".$CONFIG['admin']['base_url']."article/trash'</script>";
		
	}
}

?>
