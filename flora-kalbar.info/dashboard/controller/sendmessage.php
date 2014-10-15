<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class sendmessage extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){
       
		$data = $this->contentHelper->getMessage();
		// pr($data);
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
			}
		}
		
		// pr($data);
		$_SESSION['pages'] = 'message';
		return $this->loadView('message/viewmessage',$data);

	}
	
	public function addmessage(){
		
		$data = array();
		
		return $this->loadView('message/inputmessage',$data);
	}
    
	public function messageinp(){
		global $CONFIG;
		
		if ($_POST){
			$getUser = $this->contentHelper->get_user($_POST['receive']);
			$_POST['receive'] = $getUser[0]['id'];
			$data = $this->contentHelper->saveMessage($_POST);
			
			echo "<script>alert('Message berhasil di kirim');window.location.href='".$CONFIG['admin']['base_url']."sendmessage/index'</script>";
		}else{
			echo "<script>alert('Message gagal di kirim');window.location.href='".$CONFIG['admin']['base_url']."sendmessage/index'</script>";
		}
		
		
        
	}
	
	public function messagedel(){

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
