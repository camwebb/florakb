<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class export extends Controller {
	
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
       
		global $dbConfig;
		//ENTER THE RELEVANT INFO BELOW
		$mysqlDatabaseName =$dbConfig['name'];
		$mysqlUserName =$dbConfig['user'];
		$mysqlPassword =$dbConfig['pass'];
		$mysqlHostName =$dbConfig['host'];
		$mysqlExportPath ='Backup-'.date('Y-m-d-H:-i-s').'.sql';

		//DO NOT EDIT BELOW THIS LINE
		//Export the database and output the status to the page
		$command='mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ~/' .$mysqlExportPath;
		exec($command,$output=array(),$worked);
		switch($worked){
		case 0:
		echo 'Database <b>' .$mysqlDatabaseName .'</b> successfully exported to <b>~/' .$mysqlExportPath .'</b>';
		break;
		case 1:
		echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>~/' .$mysqlExportPath .'</b>';
		break;
		case 2:
		echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
		break;
		}

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
