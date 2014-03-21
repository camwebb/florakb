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
        $shortName = $_POST["shortName"];
        $email = $_POST["email"];
        $twitter = $_POST["twitter"];
        $website = $_POST["web"];
        $phone = $_POST["phone"];
        $pass = $_POST["pass"];
        $re_pass = $_POST["re_pass"];
         
        $checkName = $this->loginHelper->checkName($name);       
        $checkEmail = $this->loginHelper->checkEmail($email);        
        $checkTwitter = $this->loginHelper->checkTwitter($twitter);
        $checkShortName = $this->loginHelper->checkShortName($shortName);
        
        if($checkName !== true || $checkEmail !== true || $checkTwitter !== true || $checkShortName !== true){
            $statusName = "";
            $msgname = "";
            $statusEmail = "";
            $msgEmail = "";
            $statusTwitter = "";
            $msgTwitter = "";
            $statusShortname = "";
            $statusShortname = "";
            
            if($checkName !== true){
                $statusName = "exist";
                $msgName = "Name already exist";
            }
            if($checkEmail !== true){
                $statusEmail = "exist";
                $msgEmail = "Email already exist";
            }
            if($checkTwitter !== true){
                $statusTwitter = "exist";
                $msgTwitter = "Twitter already exist";
            }
            if($checkShortName !== true){
                $statusShortname = "exist";
                $msgShortname = "Shortname already exist";
            }
                echo json_encode(array('statusName' => $statusName, 'msgName' => $msgName, 'statusEmail' => $statusEmail, 'msgEmail' => $msgEmail, 'statusTwitter' => $statusTwitter, 'msgTwitter' => $msgTwitter, 'statusShortname' => $statusShortname, 'msgShortname' => $msgShortname));
                exit;
        }
        
        if($checkName && $checkEmail && $checkTwitter && $checkShortName){
            $data = array();
            $data[]= array('name'=>$name, 'shortName'=>$shortName, 'email'=>$email, 'twitter'=>$twitter, 'website'=>$website, 'phone'=>$phone, 'password'=>$pass);
            $signup = $this->loginHelper->createUser($data);
            echo json_encode(array('test' => 'test'));
            exit;
        }
        exit;
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
