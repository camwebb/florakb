<?php

/*
Note : 
	- add new field short_namecode for some table for identified record
	- Dont forget to fill personID, taxonID, etc (Foreign Key) in excel sheet references
	- rename field using to det_using, date to det_date in det table, because the fieldname is sql keywords


*/
class excelHelper extends Database {

	var $configkey = "default";
	
	function excel($file=false)
	{
		error_reporting(E_ALL ^ E_NOTICE);
		
		global $CONFIG, $EXCEL;
		if (!$file) return false;
		
		if (!in_array($_FILES[$file]['type'], $EXCEL[0]['filetype'])) return false;
		
		if (array_key_exists('admin', $CONFIG)){
			$this->configkey = 'admin';
		}
		if (array_key_exists('dashboard', $CONFIG)){
			$this->configkey = 'dashboard';
		}
		
		$excel = "";
		$filename = ($_FILES[$file]['tmp_name']);
		$excelEngine = LIBS . 'excel/excel_reader' . $CONFIG[$this->configkey]['php_ext'];
		if (is_file($excelEngine)){
			
			require_once ($excelEngine);
			
			$excel = new Spreadsheet_Excel_Reader($filename);
			logFile('load excel success');
		}
		
		return $excel;
	}
	
	function fetchExcel($formName, $sheet=1,$startRow=1,$startCol=0)
	{
		global $EXCEL;
		
			$data = array();
			$newData = array();
			
			$numberOfSheet = $sheet;
			$startRowData = $startRow;
			$startColData = $startCol;
			
			// parameternya adalah name dari input type file
			$excel = $this->excel($formName);
			
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
					
					$fieldName = "";
					if ($countRow>0){
						// looping baris
						for ($a=$startRowData; $a<=$countRow; $a++){
							
							// looping kolom
							
							for ($b=$startRowData; $b<=$countColl; $b++){
								
								$fieldName = $excel->val($startRowData, ($b), $i);
								
								$data[$i]['data'][$a][] = $excel->val($a+1, ($b), $i);
								// $data[$i]['data'][$a][0][$fieldName] = $excel->val($a+1, ($b), $i);
								
							}
							
						}
					}
					
				}
			}
			
			logFile('parse data excel success');
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
				logFile('clean data');
			}
			
			return $newData;
			
		
	}
	
	function referenceData($newData=array())
	{
		global $C_SPEC;
		if (empty($newData)) return false;
		
		$sql = array();
		$arrTmp = array();
		$ignoreTable = array(2);
		$numberTable = array(1,2,3,4);
		$defineTable = array(1=>'taxon',2=>'img',3=>'person',4=>'locn');
		
		// Img table identified
		$fieldFetch[2] = array('id','indivID','personID','md5sum','filename','directory','plantpart','notes','mimetype'); 
		$fieldConvert[2] = array('tree_id'=>'indivID','plant_part'=>'plantpart','photographer'=>'personID'); 
		
		// Taxon table identified
		$fieldFetch[1] = array('id','rank','morphotype','fam','gen','sp','subtype','ssp','auth','notes','short_namecode'); 
		$fieldConvert[1] = array('db_id'=>'id','ssp_auth'=>'auth','unique_key'=>'short_namecode'); 
		
		// Person table identified
		$fieldFetch[3] = array('id','name','email','twitter','website','phone','short_namecode'); 
		$fieldConvert[3] = array('db_id'=>'id','unique_key'=>'short_namecode'); 
		
		// Locn table identified
		$fieldFetch[4] = array('id','longitude','latitude', 'elev', 'geomorph','locality','county',
								'province','island','country','notes','short_namecode'); 
		$fieldConvert[4] = array('long'=>'longitude', 'lat'=>'latitude','geomorphology'=>'geomorph','kabupaten'=>'county',
								'unique_id'=>'short_namecode'); 
		
		$convert = 0;
		foreach ($newData as $key => $values){
			
			
			$fieldKey = @array_keys($fieldConvert[$convert]);
			if (in_array($key,$numberTable)){
				foreach ($values['data'] as $k=> $val){
					
					$keyField = array();
					$tmpField = array();
					$tmpData = array();
					$t_field = array();
					$t_data = array();
					$t_dataraw = array();
					
					foreach ($val as $keys => $v){
						
						if (!empty($fieldKey)){
						
							if (in_array($keys, $fieldKey)){
							
								// check if field excel not same with table DB, run convert field
								$keyField = $fieldConvert[$convert][$keys];
								if (in_array($keyField, $fieldFetch[$convert])){
									$keyField = $keyField;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}else $keyField = false;
								
							}else{
								// if field exist in table, then insert to array
								if (in_array($keys, $fieldFetch[$convert])){
									$keyField = $keys;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}else $keyField = false;
							}
						}
						
						// if field empty don't store to array
						if ($keyField){
							$t_field[] = $keyField;
							$t_data[] = "'$keyData'"; 
							$t_dataraw[$keyField] = $keyData; 
						}
						
					}
					
					// generate query
					$tmpField = implode(',',$t_field); 
					$tmpData = implode(',',$t_data); 
					
					if (!in_array($key,$ignoreTable)){
						$sql[$defineTable[$key]][] = "INSERT INTO {$defineTable[$key]} ({$tmpField}) VALUES ({$tmpData})";
						
						
					}
					
					
					// $arrTmp[$defineTable[$key]]['field'][] = $t_field;
					$arrTmp[$defineTable[$key]]['data'][] = $t_dataraw;
				}
				
			}
			
			$convert++;
			
		}
		// pr($arrTmp);
		
		$returnArr['query'] = $sql;
		$returnArr['rawdata'] = $arrTmp;
		
		logFile(serialize($returnArr));
		
		return $returnArr;
	}
	
	// validate field input from coll_conf.php
	function validateField($defineTable=false, $keyField=false, $v=false)
	{
		global $C_SPEC;
		
		
		if (!$defineTable && !$keyField && !$v) return false;
		
		$libsDefine = $C_SPEC[$defineTable][$keyField];
		$cleanData = addslashes($v);
		if ($libsDefine){
			list ($type, $length) = explode(',',$libsDefine);
			
			if ($type=='string'){
				if (is_string($cleanData) && strlen($cleanData)<=trim($length)) $cleanData = $cleanData;
				else $cleanData = "";
			}
			if ($type=='int'){
				if (is_int((int)$cleanData) && strlen($cleanData)<=$length) $cleanData = $cleanData;
				else $cleanData = "";
			}
		}
		
		return $cleanData;
	}
	
	function parseMasterData($newData=array(), $subtitute=array())
	{
		global $C_SPEC;
		
		if (empty($newData)) return false;
		
		$arrTmp = array();
		// pr($newData);exit;
		$sql = array();
		$numberTable = array(0);
		$defineTable = array(0=>'indiv',1=>'det', 2=>'obs',3=>'coll');
		
		// Obs table identified
		$fieldFetch[2] = array('id','indivID','date','personID','microhab','habit','dbh',
								'height','bud','flower','fruit','localname','notes','char_lf_insert_alt','char_lf_insert_opp'); 
		$fieldConvert[2] = array('unique_key'=>'indivID','obs_by'=>'personID'); 
		
		// Indiv table identified
		$fieldFetch[0] = array('id','locnID','plot'); 
		$fieldConvert[0] = array('locn'=>'locnID','unique_key'=>'id'); 
		
		// Coll table identified
		$fieldFetch[3] = array('id','collCode','dateColl','indivID','collReps','dnaColl','notes','deposit'); 
		$fieldConvert[3] = array('locn'=>'locnID','unique_key'=>'indivID'); 
		
		// Collector table identified
		// $fieldFetch[4] = array('id','collID','personID','order'); 
		// $fieldConvert[4] = array('obs_by'=>'personID'); 
		
		// Det table identified
		$fieldFetch[1] = array('id','indivID','personID','det_date','taxonID','confid','det_using','notes'); 
		$fieldConvert[1] = array('unique_key'=>'indivID','det'=>'taxonID','det_by'=>'personID',
								'det_notes'=>'notes'); 
								
		$convert = 0;
		
		foreach ($defineTable as $a => $b){
		
			foreach ($newData as $key => $values){
				
				if (in_array($key,$numberTable)){
					foreach ($values['data'] as $k=> $val){
						
						$keyField = array();
						$tmpField = array();
						$tmpData = array();
						$t_field = array();
						$t_data = array();
						$t_dataraw = array();
						
						$fieldKey = @array_keys($fieldConvert[$a]);
						foreach ($val as $keys => $v){
							
							
							
							if (in_array($keys, $fieldKey)){
							
								// check if field excel not same with table DB, run convert field
								$keyField = $fieldConvert[$a][$keys];
								if (in_array($keyField, $fieldFetch[$a])){
									$tmpkeyField = $keyField;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}else $tmpkeyField = false;
								
							}else{
								// if field exist in table, then insert to array
								if (in_array($keys, $fieldFetch[$a])){
									$tmpkeyField = $keys;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}else{
									/* inject data to table */
									if ($b == 'coll'){
										$tmpkeyField = 'collCode';
										$keyData = str_shuffle('ABCDEFGHIJ1234567890');
									}else $tmpkeyField = false;
								}
							}
							
							
							// if field empty don't store to array
							if ($tmpkeyField){
								$t_field[] = $tmpkeyField;
								$t_data[] = "'$keyData'"; 
								$t_dataraw[$tmpkeyField] = $keyData; 
							}
							
						}
						
						
						// generate query
						$tmpField = implode(',',$t_field); 
						$tmpData = implode(',',$t_data); 
						$sql[$b][] = "INSERT INTO {$b} ({$tmpField}) VALUES ({$tmpData})";
						
						$arrTmp[$b]['data'][] = $t_dataraw;
					}
					
				}
				
				$convert++;
				
			}
		}
		
		
		$returnArr['query'] = $sql;
		$returnArr['rawdata'] = $arrTmp;
		
		logFile(serialize($returnArr));
		
		return $returnArr;
	}
}

?>