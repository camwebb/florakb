<?php

class upload extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
	}
	public function loadmodule()
	{
		
		$this->collectionHelper = $this->loadModel('collectionHelper');
        $this->excelHelper = $this->loadModel('excelHelper');
	}
	
	public function index(){

		return $this->loadView('upload');

	}
	
	function parseExcel()
	{
		global $EXCEL;
		
		
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
			$parseExcel = $this->excelHelper->fetchExcel($formName, $numberOfSheet,$startRowData,$startColData);
			
			
			if ($parseExcel){
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
				
				if ($newData){
					$referenceQuery = $this->excelHelper->referenceData($newData);
					
					$masterQuery = $this->excelHelper->parseMasterData($newData);
					$masterQuery['rawdata']['img'] =  $referenceQuery['rawdata']['img'];
					
					
					// pr($masterQuery['rawdata']);
					// exit;
					
					$priority = array('taxon','locn','person');
					$masterPriority = array('indiv','img','det','obs','coll','collector');
					
					$param['ref'] = $referenceQuery;
					$param['ref_priority'] = $priority;
					$param['master'] = $masterQuery;
					$param['master_priority'] = $masterPriority;
					
					
					$insertData = $this->collectionHelper->insertCollFromExcel($param);
					
					$endTime = microtime(true);
					
					if ($insertData) echo 'Insert Success on '. execTime($startTime,$endTime);
					else echo 'insert data failed';
				}
			}
		}
	}
	
}

?>
