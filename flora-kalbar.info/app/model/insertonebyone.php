<?php

class insertonebyone extends Database {	
	
	function insertData($table=false, $data=array(), $db2=false)
	{
		if (!$table and empty($data)) return false;

		$return = array();
		$return['status'] = false;
		
		foreach ($data as $key=>$val){
            if(!empty($val)){
                $tmpfield[] = $key;
                $tmpvalue[] = "'{$val}'";
            }
		}
		
		$field = implode (',',$tmpfield);
		$value = implode (',',$tmpvalue);
		
		$sql = "INSERT INTO {$table} ({$field}) VALUES ({$value})";
        
        if($db2){
            $res = $this->query($sql,1);
        }else{
            $res = $this->query($sql);
        }
        
		if ($res){
			$return['lastid'] = $this->insert_id();
			$return['status'] = true;
		}
		return $return;
	}
    
    function insertTransaction($table=false, $data=array()){
        
        global $CONFIG;
        
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
		  
            // if table person, insert generated password
            if($table == 'person'){
                $salt = $CONFIG['default']['salt'];
                
                //this is the generated password
                $genPass = $this->generate_pass();
                
                //this is the encrypted password
                $password = sha1($genPass.$salt);
                
                //insert password id, password, salt
                $dataPass = array('id' => $insert['lastid'], 'password' => $password, 'salt' => $salt);
                $insert_dataPas = $this->insertData('florakb_person',$dataPass,true);
                
                if ($insert_dataPas['status'] == 0){
        			$this->rollback();
        			logFile('====ROLLBACK TRANSACTION====');
        			return false;
        		}else{
                    $this->commit();
        			logFile('====COMMIT TRANSACTION====');
        			return true;
        		}
            }else{
                $this->commit();
    			logFile('====COMMIT TRANSACTION====');
    			return true;
            }
		}
        
        return $insert;
		exit;
    }
    
    function generate_pass($length = 8){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
    
        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
    
        return $result;
    }
}

?>