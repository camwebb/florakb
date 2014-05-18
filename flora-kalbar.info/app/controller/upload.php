<?php
if(!$_SESSION){
    header('Location: '.$basedomain);
}

class upload extends Controller {
	
	var $models = FALSE;
	var $view;
	var $user;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
        $this->user = $this->isUserOnline();
	}
	public function loadmodule()
	{
		
		$this->collectionHelper = $this->loadModel('collectionHelper');
        $this->excelHelper = $this->loadModel('excelHelper');
        $this->userHelper = $this->loadModel('userHelper');
	}
	
	public function index(){

		$username = $this->user['login']['username'];
		
		logFile("", $username, true);
		logFile("Begin upload", $username);

		return $this->loadView('upload');

	}
	
	function parseExcel()
	{
		/*
		New scenario !
		1. Parse data xls 
		2. Validate data before upload
		3. Store data to tmp table
		3. Try to move data from tmp table to real table
		4. Done
		
		*/
		
		global $EXCEL;
		
		$username = $this->user['login']['username'];
		
		
		if ($_FILES){
			
			$numberOfSheet = 5;
			$startRowData = 1;
			$startColData = 1;
			$formNametmp = array_keys($_FILES);
			$formName = $formNametmp[0];
			
			if (empty($formName)) die;
			
			$startTime = microtime(true);
			/* parse data excel */
			
			logFile('load excel begin');

			// empty log file
			

			$parseExcel = $this->excelHelper->fetchExcel($formName, $numberOfSheet,$startRowData,$startColData);
			
			
			if ($parseExcel){
				logFile('Extract File ', $username);
				foreach ($parseExcel as $key => $val){
					
					$field = implode(',',$val['field_name']);
					
					$data = array();
					foreach ($val['data'] as $keys => $value){
						
						foreach ($value as $k => $v){
							$data[$val['field_name'][$k]] = $v;
						}
						
						$newData[$val['sheet']]['data'][] = $data; 
						
					}
					
				}
				
				/* here begin process */
				if ($newData){
					
					$emptyTmptable = $this->collectionHelper->truncateData(false,true);
					
					if ($emptyTmptable){
						
						logFile('empty tmp table before insert');
						sleep(1);
						$referenceQuery = $this->collectionHelper->tmp_data($newData);

					}
					// pr($newData);
					
					logFile('Preparing database ', $username);
					$insertData = false;
					// $referenceQuery = true;
					if ($referenceQuery){
						
						$this->collectionHelper->startTransaction();
						
						$getRef = $this->collectionHelper->getRefData($newData);
						$referenceQuery = $this->excelHelper->referenceData($getRef);
						
						$insertRef = $this->collectionHelper->storeRefData($referenceQuery);
						
						$getMaster = $this->collectionHelper->getMasterData();
						// insert indiv
						$indivQuery = $this->excelHelper->parseMasterData($getMaster,true);
						$insertIndiv = $this->collectionHelper->storeIndivData($indivQuery);
						
						// insert det,obs,coll
						sleep(1);
						$getMaster = $this->collectionHelper->getMasterData();
						$masterQuery = $this->excelHelper->parseMasterData($getMaster);
						$insertIndiv = $this->collectionHelper->storeMasterData($masterQuery);
						
						// insert collector
						$getMaster = $this->collectionHelper->getMasterData();
						$collectorQuery = $this->excelHelper->parseMasterData($getMaster,true,5,'collector');
						$insertCollector = $this->collectionHelper->storeSingleData($collectorQuery);
						
						$getMaster = $this->collectionHelper->getMasterData(true,'tmp_photo');
						$imgQuery = $this->excelHelper->parseMasterData($getMaster,true,4,'img');
						$insertImage = $this->collectionHelper->storeSingleData($imgQuery,'img');
						// pr($imgQuery);
						if ($insertImage){

							$this->logUploadUser($_FILES[$formName]['name']);
							sleep(1);
							$this->collectionHelper->commitTransaction();
							$insertData = true;
						}else{
							$this->collectionHelper->rollbackTransaction();
						}
						
					}
					
					// exit;
					/*
					[Old script]
					
					$masterQuery = $this->excelHelper->parseMasterData($newData);
					$masterQuery['rawdata']['img'] =  $referenceQuery['rawdata']['img'];
					
					$priority = array('taxon','locn','person');
					$masterPriority = array('indiv','img','det','obs','coll','collector');
					
					$param['ref'] = $referenceQuery;
					$param['ref_priority'] = $priority;
					$param['master'] = $masterQuery;
					$param['master_priority'] = $masterPriority;
					
					$insertData = $this->collectionHelper->insertCollFromExcel($param);
					*/ 
					$endTime = microtime(true);
					
					if ($insertData){
						logFile('Insert xls success');

						print json_encode(array('status'=>true, 'finish'=>true, 'msg'=>'Insert success  ('. execTime($startTime,$endTime).')'));
						// echo 'Insert success  ('. execTime($startTime,$endTime).')';	
						
						exit;
					}else{
						logFile('Insert xls failed');
						// echo 'Insert data failed';	
						print json_encode(array('status'=>false, 'msg'=>'Insert data failed'));
						exit;
					} 
					
					
				}
			}
		}else{
			logFile('File xls empty');
			// echo "File is empty";
			print json_encode(array('status'=>true, 'msg'=>'File is empty'));
		}
		
		
		exit;
	}
	

	function showUploadProcess()
	{
		
		global $CONFIG;

		$dataArr['file'] = "PHPTail";
		$dataArr['path'] = LIBS.'phptail/';

		require './'.LIBS.'phptail/PHPTail.php';
		// $phpTail = $this->load($dataArr);

		$fileName = $CONFIG['default']['root_path']."/logs/".$this->user['login']['username'];
		
		
		$phpTail = new PHPTail($fileName);
		// pr($phpTail);
		if(isset($_GET['ajax']))  {

		        echo $phpTail->getNewLines($_GET['lastsize']);
		        die();
		}else{
			
			echo json_encode(array('size'=> filesize($fileName)));
		}
		
		// echo $phpTail->getNewLines();
		// $phpTail->ada();
		// $phpTail->generateTemplate();

		exit;
	}

	function truncate()
	{
		$this->collectionHelper->truncateData(false,true);
	}
	
	function logUploadUser($file)
	{

		global $CONFIG;
		$fileName = $CONFIG['default']['root_path']."/logs/".$this->user['login']['username'];
		$data = json_encode(array('data'=>file_get_contents($fileName)));
		
		$storeLog = $this->userHelper->storeUserUploadLog($data, $file);
		return true;
	}

	

}

?>
