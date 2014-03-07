<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class home extends Controller {
	
	var $models = FALSE;
	var $view;

	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
    }
	
	function loadmodule()
	{
        //$this->models = $this->loadModel('frontend');
        $this->loginHelper = $this->loadModel('loginHelper');
	}
	
	function index(){
    	return $this->loadView('home');
    }
	
    function signup(){
        
        $name = $_POST["name"];
        $email = $_POST["email"];
        $twitter = $_POST["twitter"];
        $website = $_POST["web"];
        $phone = $_POST["phone"];
        $pass = $_POST["pass"];
        $re_pass = $_POST["re_pass"];
        
        //Do Validation first
        // 1. Name, email, password and re-password are required
        // 2. match password and re-password
        // 3. is email exist?
        // 4. is twitter exist?
        //Insert into 2 database
        // 1. name, email, twitter, web, and phone into florakb[person]
        // 2. password and salt into app[florakb_person]
        // 3. if data optional are empty set null
        
        $data = array();
        $data[]= array('name'=>$name, 'email'=>$email, 'twitter'=>$twitter, 'website'=>$website, 'phone'=>$phone, 'password'=>$pass);
        //pr($data);
        $signup = $this->loginHelper->createUser($data);
        
    }
	
	function fetchExcel($sheet=1,$startRow=1,$startCol=0)
	{
		global $EXCEL;
		
			$data = array();
			$newData = array();
			
			$numberOfSheet = $sheet;
			$startRowData = $startRow;
			$startColData = $startCol;
			
			// parameternya adalah name dari input type file
			$excel = $this->excel('tes');
			
			if ($excel){
			
				for ($i=0; $i<$numberOfSheet; $i++){
					
					$data[$i]['sheet'] = $i;
					
					// get field name in current sheet
					$countColl = $excel->colcount($sheet_index=$i);
					$countRow = $excel->rowcount($sheet_index=$i);
					if ($countColl>0){
						for ($a=$startRowData; $a<=$countColl; $a++){
							$data[$i]['field_name'][] = $excel->val($startRowData, ($a), $i);
						}
					}
					
					if ($countRow>0){
						// looping baris
						for ($a=$startRowData; $a<=$countRow; $a++){
							
							// looping kolom
							
							for ($b=$startRowData; $b<=$countColl; $b++){
								
								$data[$i]['data'][$a][] = $excel->val($a+1, ($b), $i);
								
							}
							
						}
					}
					
				}
			}
			
			
			// clean data, if empty pass
			if ($data){
				foreach ($data as $key=>$val){
					
					
					foreach ($val['data'] as $keys=>$values){
						
						$newData[$key]['sheet'] = $val['sheet'];
						$newData[$key]['field_name'] = $val['field_name'];
						
						if (!empty($values[0])){
							
							$newData[$key]['data'][$keys] = $values;
						}
					}
				
				}
			}
			
			return $newData;
			
		
	}
	
	function parseExcel()
	{
		global $EXCEL;
		if ($_FILES){
			
			$numberOfSheet = 5;
			$startRowData = 1;
			$startColData = 1;
			
			$parseExcel = $this->fetchExcel($numberOfSheet,$startRowData,$startColData);
			
			pr($parseExcel);
		}
	}
}

?>
