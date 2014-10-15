<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class setting extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->models = $this->loadModel('msetting');
	}
	
	public function usr_setting($status=null){
		
		$data['item'] = $this->models->get_user($_SESSION['admin']['id']);
		
		if($status) $data['status']=1;
		
		if(isset($_GET['status'])) $data['status'] = 0;
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('page-settings',$data);

	}
	
	public function administrator(){
		
		$data['item'] = $this->models->get_user_list(1);
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user/user-admin',$data);

	}
	
	public function operator(){
		
		$data['item'] = $this->models->get_user_list(2);
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user/user-operator',$data);

	}
	public function koordinator(){
		
		$data['item'] = $this->models->get_user_list(3);
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user/user-koordinator',$data);

	}
	
	public function jobseeker(){
		
		$data['item'] = $this->models->get_user_list('5,6,7');
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user/user-jobseeker',$data);

	}
	
	public function corporate(){
		
		$data['item'] = $this->models->get_user_list(4);
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user/user-corporate',$data);

	}
	
	public function crt_user(){
		
		$data['item'] = $this->models->get_user_list();
		
		$_SESSION['pages'] = 'user';
		
		return $this->loadView('user-list',$data);

	}
	
	public function usr_profileupd(){
		global $CONFIG;

		if(!empty($_POST)){
			$x = form_validation($_POST);

			   try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						
						$x['action'] = 'update';
						
						//upload file
						if(!empty($_FILES)){
							// pr($_FILES);
							// exit;
							if($_FILES['file_image']['name'] != ''){
								if($x['action'] == 'update') deleteFile($x['image']);
								$image = uploadFile('file_image');
								$x['image'] = $image['filename'];
							}
						}
						$data = $this->models->upd_profile($x);
			   		}
			}catch (Exception $e){}
			
			return $this->usr_setting(1);
		} else {
			echo "<script>window.location.href='".$CONFIG['admin']['base_url']."setting/usr_setting'</script>";
		}
	}
	
	public function usr_passupd(){
		global $CONFIG;

		if(!empty($_POST)){
			$x = form_validation($_POST);

			   try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						$pass = sha1($x['new-password-1'].$_SESSION['admin']['salt']);
						$data = $this->models->upd_pass($pass);
			   		}
			}catch (Exception $e){}
			
			return $this->usr_setting(1);
		} else {
			echo "<script>window.location.href='".$CONFIG['admin']['base_url']."setting/usr_setting'</script>";
		}
	}
	
	public function adduser(){
		return $this->loadView('inputuser');
	}
	
	public function checkpass(){
		$oldpass = $_POST['old_pass'];
		
		if(sha1($oldpass.$_SESSION['admin']['salt'])==$_SESSION['admin']['password']){
			$getData = array('status'=>"1");
		} else {
			$getData = array('status'=>"0");
		}
		
		echo json_encode($getData);
		
		exit;
	}
	
	public function checkuser(){
		$new_user = $_POST['new_user'];

		$result = $this->models->user_check($new_user);

		if($result[0]['count'] > 0){
			$getData = array('status'=>"1");
		} else {
			$getData = array('status'=>"0");
		}
		
		echo json_encode($getData);
		
		exit;
	}
	
	public function inputuser(){
		global $CONFIG;
		// pr($_POST);
		// exit;
		if(!empty($_POST)){
			$x = form_validation($_POST);

			   try
			   {
			   		if(isset($x) && count($x) != 0)
			   		{
						//update or insert
						if($x['id'] != ''){
							$x['action'] = 'update';
							$x['password'] = sha1($x['password'].$_SESSION['admin']['salt']);
						} else {
							$x['action'] = 'insert';
							$x['password'] = sha1($x['password'].$_SESSION['admin']['salt']);
						}
						
						//upload file
						if(!empty($_FILES)){
							if($_FILES['file_image']['name'] != ''){
								if($x['action'] == 'update') deleteFile($x['image']);
								$image = uploadFile('file_image');
								$x['image'] = $image['filename'];
							}
						}
						
						$data = $this->models->input_usr($x);
			   		}
			}catch (Exception $e){}
			
		} 
			echo "<script>window.location.href='".$CONFIG['admin']['base_url']."setting/crt_user'</script>";
		
	}
	
	public function del_usrmember(){
		global $CONFIG;
		
		$id = $_GET['id'];
		
		$data = $this->models->usrmember_del($id);
		
		echo "<script>window.location.href='".$CONFIG['admin']['base_url']."setting/crt_user'</script>";
	}
	
	public function upd_usrmember(){

		$id = $_GET['id'];
		
		$data['item'] = $this->models->get_usr_id($id);
		
		return $this->loadview('inputuser',$data);
		
		
	}

}

?>
