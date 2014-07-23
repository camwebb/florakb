<?php
// defined ('MICRODATA') or exit ( 'Forbidden Access' );

class taxon extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();
		$this->loadmodule();
		
		global $basedomain;	
		$validate = $this->validateToken();
		if (!$validate){
			redirect($basedomain);
			exit;
		} 
		// $this->validatePage();
	}
	public function loadmodule()
	{
		
		$this->browseHelper = $this->loadModel('browseHelper');
	}
	
	public function index(){
       	
       	pr($_GET);

       	exit;
		// $data = $this->models->get_profile();
		// pr($data);
		return $this->loadView('viewprofile',$data);

	}
    
	function getData()
	{
		// pr($_GET);


		$family =  $this->browseHelper->detailIndiv(1);
		// pr($family);
		if ($family){
			print json_encode($family);
		}


		exit;
		/*
			
			1. jika tidak ada parameter kedua maka get data 
		*/
	}

	function getFamily()
	{
		pr($_GET);


		/*
			
			1. jika tidak ada parameter kedua maka get data 
		*/
	}

	function getGenus()
	{
		pr($_GET);


		/*
			
			1. jika tidak ada parameter kedua maka get data 
		*/
	}

	function getSpecies()
	{
		pr($_GET);


		/*
			
			1. jika tidak ada parameter kedua maka get data 
		*/
	}

	function validateToken()
	{

		$_SESSION['services']['token'] = '123';
		$token = $_SESSION['services'];
		if ($token['token']) return true;
		return false;
	}

	function decode()
	{
		global $basedomain;
		
		$decode = decodeJson($basedomain."taxon/getData");
		pr($decode);
	}
}

?>
