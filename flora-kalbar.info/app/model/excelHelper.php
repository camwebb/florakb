<?php
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
								// pr($fieldName);
								$data[$i]['data'][$a][] = $excel->val($a+1, ($b), $i);
								// $data[$i]['data'][$a][0][$fieldName] = $excel->val($a+1, ($b), $i);
								
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
	
	function generateQuery($newData=array())
	{
		global $C_SPEC;
		// pr($C_SPEC);
		if (empty($newData)) return false;
		
		$sql = array();
		$numberTable = array(0,1,3,4);
		$defineTable = array(0=>'coll', 1=>'taxon',3=>'person',4=>'locn');
		
		// Taxon table identified
		// $fieldFetch[0] = array('id', 'collCode','dateColl','indivID','collReps','dnaColl','notes','deposit'); 
		// $fieldConvert[0] = array('db_id'=>'id','ssp_auth'=>'auth'); 
		
		// Taxon table identified
		$fieldFetch[1] = array('id','rank','morphotype','fam','gen','sp','subtype','ssp','auth','notes'); 
		$fieldConvert[1] = array('db_id'=>'id','ssp_auth'=>'auth'); 
		
		// Person table identified
		$fieldFetch[3] = array('id','name','email','twitter','website','phone'); 
		$fieldConvert[3] = array('db_id'=>'id'); 
		
		// Person table identified
		$fieldFetch[4] = array('id','longitude','latitude', 'elev', 'geomorph','locality','county',
								'province','island','country','notes'); 
		$fieldConvert[4] = array('long'=>'longitude', 'lat'=>'latitude','geomorphology'=>'geomorph','kabupaten'=>'county'); 
		
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
					
					foreach ($val as $keys => $v){
						
						if (!empty($fieldKey)){
						
							if (in_array($keys, $fieldKey)){
							
								// check if field excel not same with table DB, run convert field
								$keyField = $fieldConvert[$convert][$keys];
								if (in_array($keyField, $fieldFetch[$convert])){
									$keyField = $keyField;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}
								
							}else{
								// if field exist in table, then insert to array
								if (in_array($keys, $fieldFetch[$convert])){
									$keyField = $keys;
									
									// check collection libs before
									$keyData = $this->validateField($defineTable[$key], $keyField, $v);
								}
							}
						}
						
						// if field empty don't store to array
						if ($keyField){
							$t_field[] = $keyField;
							$t_data[] = "'$keyData'"; 
						}
						
					}
					
					// generate query
					$tmpField = implode(',',$t_field); 
					$tmpData = implode(',',$t_data); 
					$sql[$defineTable[$key]][] = "INSERT INTO {$defineTable[$key]} ({$tmpField}) VALUES ({$tmpData})";
					
					
				}
				
			}
			
			$convert++;
			
		}
		
		return $sql;
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
}

?>