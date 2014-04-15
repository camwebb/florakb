<?php

class insertonebyone extends Database {	
	
	function insertData($table=false, $data=array())
	{
		if (!$table and empty($data)) return false;

		$return = array();
		$return['status'] = false;
		
		foreach ($data as $key=>$val){
			$tmpfield[] = $key;
			$tmpvalue[] = "'{$val}'";
		}
		
		$field = implode (',',$tmpfield);
		$value = implode (',',$tmpvalue);
		
		$sql = "INSERT INTO {$table} ({$field}) VALUES ({$value})";
		$res = $this->query($sql);
		if ($res){
			
			$return['lastid'] = $this->insert_id();
			$return['status'] = true;
		}
		return $return;
	}
    
    function insertTransaction($table=false, $data=array()){
        
        if (!$table and empty($data)) return false;
        
        $startTransaction = $this->begin();
		if (!$startTransaction) return false;
		logFile('====TRANSACTION READY====');

		$insert = $this->insertData($table,$data);
	    
		if ($insert['status'] == 0){
			$this->rollback();
			logFile('====ROLLBACK TRANSACTION====');
			return false;
		}else{
			$this->commit();
			logFile('====COMMIT TRANSACTION====');
			return true;
		}
        
        return $insert;
		exit;
    }
}

?>