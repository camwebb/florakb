<?php

class Application {
	
	var $page;
	var $func;
	var $configkey = 'default';
	
	
	protected $php_ext = "";
	protected $sessi = "";
	protected $user = "";
	
	public function __construct(){
		global $CONFIG, $DATA, $LOCALE;
		
		if (array_key_exists('admin', $CONFIG)){
			$this->configkey = 'admin';
			
		}
		if (array_key_exists('dashboard', $CONFIG)){
			$this->configkey = 'dashboard';
		}
		$this->php_ext = $CONFIG[$this->configkey]['php_ext'];
		$this->page = $DATA[$this->configkey]['page'];
		$this->func = $DATA[$this->configkey]['function'];
		
		
		
	}
	
	
	
	function loadView($fileName='home', $data="")
	{
		global $CONFIG, $basedomain, $app_domain;
		
		if ($fileName == "") return false;
		if (array_key_exists('admin', $CONFIG)){
			$this->configkey = 'admin';
		}
		$getFileView = null;
		$php_ext = $CONFIG[$this->configkey]['php_ext'];
		
		if ($data !=''){
			/* Ubah subkey menjadi key utama */
			foreach ($data as $key => $value){
				$$key = $value;
			}
		}
		
		
		/* include file view */
		if (is_file(APP_VIEW.$fileName.$php_ext)) {
			if ($fileName !='') $fileName = $fileName.$php_ext;
			
			if (file_exists(APP_VIEW.$fileName)){
			
				ob_start();
				include APP_VIEW.$fileName;
				// $this->view->display(APP_VIEW.$fileName);
				$getFileView = ob_get_contents();
				ob_end_clean();
				
				return $getFileView;
			}else{
				show_error_page('File not exist');
				die();
			}
			
		}else{
			show_error_page('File not exist');
			die();
		}
		
		//return TRUE;
	}
	
	
	
	function load($param=false)
	{
		
		if (!$param) return false;
		
		if ($param['file'] !='') $fileName = $param['file'].'.php';
		
		if (is_file($param['path'].$fileName)){
		
			include $param['path'].$fileName;
			
			$$param['file'] = new $param['file']();
			
			ob_get_clean();
			return $$param['file'];
		}
		
		return false;
	}
	
	function loadSessi($param=null)
	{
		global $CONFIG;
		
		if ($param =="") return false;
		
		$filename = COREPATH.$param.$CONFIG[$this->configkey]['php_ext'];
		if (file_exists($filename)){
			
			$include = include $filename;
			$object = new Session();
			
			ob_get_clean();
			
			return $object;
			
		}
		
		return false;
		
	}
	
	function loadModel($fileName=false)
	{
		global $CONFIG;
		
		if (!$fileName) return false;
		$dataArr = array();
		
		if (array_key_exists('admin', $CONFIG)){
			$this->configkey = 'admin';
		}
		
		$php_ext = $CONFIG[$this->configkey]['php_ext'];
		if (is_file(APP_MODELS.$fileName.$php_ext)) {
			
			$dataArr['file'] = $fileName;
			$dataArr['path'] = APP_MODELS;
			return $this->load($dataArr);
			
		}
		
		return false;
		
	}
	
	/* under develope */
	// function assign($key, $data)
	// {
		// return array($key => $data);
	// }
	
	
	function validatePage()
	{
		global $basedomain, $CONFIG, $DATA;
		if (!$this->isUserOnline()){
			
			redirect($basedomain.$CONFIG[$this->configkey]['login']);
			exit;
		}else{
		
			if ($DATA[$this->configkey]['page'] == $CONFIG[$this->configkey]['login']){
				
				redirect($basedomain.$CONFIG[$this->configkey]['default_view']);
				exit;
			}
		}
		
	}
	
	
}

?>
