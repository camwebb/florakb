<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class slideshow extends Controller {
	
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
		
		$data = $this->models->get_article_slide();
		if ($data){
			foreach ($data as $key => $val){
				$data[$key]['createdateChange'] = changeDate($val['createdate']);
				$data[$key]['postdateChange'] = changeDate($val['postdate']);
			}
		}
		
		$_SESSION['pages'] = 'slideshow';
		return $this->loadView('slideshowview',$data);

	}
	
	public function activator(){
		global $CONFIG;
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			
			$data = $this->models->slide_activator($id);
			
		}
		
		 echo "<script>window.location.href='".$CONFIG['admin']['base_url']."slideshow/index'</script>";
	}
	
	public function remove(){
		global $CONFIG;
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			
			$data = $this->models->slide_remove($id);
			
		}
		
		 echo "<script>window.location.href='".$CONFIG['admin']['base_url']."slideshow/index'</script>";
	}
}

?>
