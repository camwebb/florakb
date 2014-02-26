<?php
class collectionHelper extends Database {


	function insertCollFromExcel($newData=array())
	{
		
		if (empty($newData)) return false;
		
		$numberTable = array(0,1);
		$defineTable = array(0=>'coll', 1=>'taxon');
		$taxonFieldConvert = array();
		
		foreach ($newData as $key => $values){
						
			if (in_array($key,$numberTable)){
			// echo 'ada';
				foreach ($values['data'] as $k=> $val){
					
					// pr($val);
					foreach ($val as $keys => $v){
						
						// echo $keys;
						$t_field[] = $keys;
						$t_data[] = "'$v'"; 
					}
					
					$tmpField = implode(',',$t_field); 
					$tmpData = implode(',',$t_data); 
					$sql[] = "INSERT INTO {$defineTable[$key]} ({$tmpField}) VALUES ({$tmpData})";
					$tmpField = array();
					$tmpData = array();
					$t_field = array();
					$t_data = array();
					
				}
				
			}
			
		}
		
		$sql = "INSERT INTO taxon (unique_key,db_id,morphotype,fam,gen,sp,subtype,ssp,ssp_auth) VALUES ('Rubiaceae','123','','Rubiaceae','','','','','')";
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