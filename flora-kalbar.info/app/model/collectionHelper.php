<?php
class collectionHelper extends Database {


	function insertCollFromExcel($newData=array())
	{
		
		if (empty($newData)) return false;
		
		$numberTable = array(0,1);
		$defineTable = array(0=>'coll', 1=>'taxon');
		
		// Taxon table identified
		$fieldFetch[1] = array('id','rank','morphotype','fam','gen','sp','subtype','ssp','auth','notes'); 
		$fieldConvert[1] = array('db_id'=>'id','ssp_auth'=>'auth'); 
		
		// pr($newData);
		
		$convert = 0;
		foreach ($newData as $key => $values){
			
			$fieldKey = array_keys($fieldConvert[$convert]);
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
									$keyData = $v;
								}
							}else{
								// if field exist in table, then insert to array
								if (in_array($keys, $fieldFetch[$convert])){
									$keyField = $keys;
									$keyData = $v;
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
		
		// $sql = "INSERT INTO taxon (unique_key,db_id,morphotype,fam,gen,sp,subtype,ssp,ssp_auth) VALUES ('Rubiaceae','123','','Rubiaceae','','','','','')";
		// $this->query($sql);
		
		if (is_array($sql)){
			foreach ($sql as $val){
				
				// $this->query($val);
			}
			
			// return true;
		}
		pr($sql);
	}
}

?>