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
	
	public function index($operatingSystem=2){
       
		global $dbConfig, $CONFIG,$basedomain;
		//ENTER THE RELEVANT INFO BELOW
		$mysqlDatabaseName =$dbConfig['name'];
		$mysqlUserName =$dbConfig['user'];
		$mysqlPassword =$dbConfig['pass'];
		$mysqlHostName =$dbConfig['host'];
		$mysqlExportPath ='Backup-'.date('Y-m-d').'.sql';
		
		$rootPath = $CONFIG['admin']['root_path'].'/backup/';
		//DO NOT EDIT BELOW THIS LINE
		//Export the database and output the status to the page
		
		if ($operatingSystem==1){
			$command = "mysqldump --opt --skip-extended-insert --complete-insert --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName." > ".$rootPath.$mysqlExportPath;
		}else{
			$command = "\"D:\\xampp\\mysql\\bin\\mysqldump.exe\" --opt --skip-extended-insert --complete-insert --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName." > ".$rootPath.$mysqlExportPath;
			
		}
		
		echo system($command);
		
		// echo 'Database <b>' .$mysqlDatabaseName .'</b> successfully exported to <b> ' .$mysqlExportPath .'</b>';
		echo "<script>alert('success export');window.location.href='".$basedomain."'</script>";
		exit;
		
	}
	
	public function import(){
		
		global $CONFIG, $basedomain;
		$data = array();
		$operatingSystem = 2;
		
		if (_p('token')){
			
			if(!empty($_FILES['db']['name'])){
				// pr($_FILES);
				$files_cover = uploadFile('db','/backup','root_path'); 
				// pr($files_cover);
				if ($files_cover['status']){
					$save = $this->contentHelper->importData($files_cover['filename']);
					// echo '1';
					$filePath = $CONFIG['admin']['root_path'].'/backup/';
					if ($save){
						
						// echo '2';
						if ($operatingSystem==1){
							$command = "mysql --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName." < ".$filePath.$files_cover['filename'];
						}else{
							$command = "\"D:\\xampp\\mysql\\bin\\mysql.exe\" --opt --skip-extended-insert --complete-insert --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName." < ".$filePath.$files_cover['filename'];
							
						}
						// echo $command;
						echo system($command);
						echo "<script>alert('success export');window.location.href='".$basedomain."'</script>";
					}
				}
				// redirect($basedomain);
				
			}
		}
		return $this->loadView('import',$data);
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
