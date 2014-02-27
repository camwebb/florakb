<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

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
	}
	
	public function index(){
        
		$var = array(1,2,3);

		$this->view->assign('test',$var);

		return $this->loadView('upload');

	}
    
    /**
     * @todo upload zip function basedomain/upload/zip
     * 
     * @see s_linux_unzip Function
     * @see unzip Function
     * @see createFolder Function
     * 
     * */
    function zip(){
        global $CONFIG;
        
        $name = 'imagezip';
        $path = '';
        $username = $_POST['username'];
        
        $uploaded_file = uploadFile($name, $path);
        if($uploaded_file['status'] != '0'){
            $path_extract = $uploaded_file['full_path'].$uploaded_file['raw_name'];
            $file = $uploaded_file['full_path'].$uploaded_file['full_name'];
            
            if($CONFIG['default']['unzip'] == 's_linux'){
                s_linux_unzip($file, $path_extract);
            }elseif($CONFIG['default']['unzip'] == 'zipArchive'){
                unzip($file, $path_extract);
            }
            
            $path_data = 'public_assets/';
            $path_user = $path_data.$username.'/';
            $path_img = $path_user.'/img';
            $path_img_ori = $path_img.'/ori';
            $path_img_500px = $path_img.'/500px';
            $path_img_100px = $path_img.'/100px';
            
            $toCreate = array($path_user, $path_img, $path_img_ori, $path_img_500px, $path_img_100px);
            $permissions = 0755;
            createFolder($toCreate, $permissions);
            
            echo json_encode(array('status' => 'error', 'message' => 'belom selesai'));
        }else{
            echo json_encode(array('status' => 'error', 'message' => $uploaded_file['message']));
        }
        exit;
    }
    
    function validateUsername(){
        $username = $_POST['username'];
        echo json_encode(array('status' => "success"));
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
	
	function parseExcel()
	{
		global $EXCEL;
		
		
		
		if ($_FILES){
			
			$numberOfSheet = 5;
			$startRowData = 1;
			$startColData = 1;
			
			$parseExcel = $this->fetchExcel($numberOfSheet,$startRowData,$startColData);
			
			
			// pr($parseExcel);
			// exit;
			if ($parseExcel){
				foreach ($parseExcel as $key => $val){
					
					
					$field = implode(',',$val['field_name']);
					
					foreach ($val['data'] as $keys => $value){
						
						foreach ($value as $k => $v){
							$data[$val['field_name'][$k]] = $v;
						}
						
						$newData[$val['sheet']]['data'][] = $data; 
						$data = array();
					}
					
					
					
				}
				
				if ($newData){
					$generateQuery = $this->generateQuery($newData);
					
					pr($generateQuery);
				}
				// $insertData = $this->collectionHelper->insertCollFromExcel($newData);
				
				
			}
		}
	}
	
	function generateQuery($newData=array())
	{
		global $C_SPEC;
		// pr($C_SPEC);
		if (empty($newData)) return false;
		
		$numberTable = array(1,3,4);
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
					
					$keyField = "";
					foreach ($val as $keys => $v){
						
						if (!empty($fieldKey)){
						
							if (in_array($keys, $fieldKey)){
							
								// check if field excel not same with table DB
								$keyField = $fieldConvert[$convert][$keys];
								if (in_array($keyField, $fieldFetch[$convert])){
									$keyField = $keyField;
									
									// check collection libs before
									$libsDefine = $C_SPEC[$defineTable[$key]][$keyField];
									$cleanData = addslashes($v);
											
									if ($libsDefine){
										list ($type, $length) = explode(',',$libsDefine);
										if ($type=='string'){
											if (is_string($cleanData) && strlen($cleanData)<=trim($length)) $cleanData = $cleanData;
											else $cleanData = "";
										}	
										
										if ($type=='int'){
											if (is_int((int)$cleanData) && strlen($cleanData)<= trim($length)) $cleanData = $cleanData;
											else $cleanData = "";
										}
									}
									$keyData = $cleanData;
								}
								
							}else{
								// if field exist in table, then insert to array
								if (in_array($keys, $fieldFetch[$convert])){
									$keyField = $keys;
									
									// check collection libs before
									$libsDefine = $C_SPEC[$defineTable[$key]][$keyField];
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
									$keyData = $cleanData;
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
					$sql[] = "INSERT INTO {$defineTable[$key]} ({$tmpField}) VALUES ({$tmpData})";
					$tmpField = array();
					$tmpData = array();
					$t_field = array();
					$t_data = array();
					
				}
				
			}
			
			$convert++;
			
		}
		
		return $sql;
	}
	
}

?>
