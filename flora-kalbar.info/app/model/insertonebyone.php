<?php

class insertonebyone extends Database {	
	
    /**
     * @todo insert a record to database
     * 
     * @param $table = table name
     * @param $data = array data to insert
     * @param $db2 = boolean using second database or not
     * 
     * @return lastid = id of inserted data
     * @return status = boolean status of data
     * 
     * */
	function insertData($table=false, $data=array(), $db2=false)
	{
		if (!$table and empty($data)) return false;

		$return = array();
		$return['status'] = false;
		
		foreach ($data as $key=>$val){
            if(!empty($val)){
                $tmpfield[] = "`$key`";
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
    
    /**
     * @todo insert data transaction
     * 
     * @param $table = table name
     * @param $data = array data to insert
     * @param $db2 = boolean using second database or not
     * 
     * @return $insert = status and last id of inserted data
     * 
     * */
    function insertTransaction($table=false, $data=array()){
        
        global $CONFIG;
        
        if (!$table and empty($data)) return false;
        
        $startTransaction = $this->begin();
		if (!$startTransaction) return false;
        
		logFile('====TRANSACTION READY====');
        if($table == 'person'){
            $dataPerson = array(
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'twitter' => $data['twitter'],
                    'website' => $data['website'],
                    'phone' => $data['phone']
                );
            $insert = $this->insertData($table,$dataPerson);
        }else{
            $insert = $this->insertData($table,$data);
        }
	    
		if ($insert['status'] == 0){
			$this->rollback();
			logFile('====ROLLBACK TRANSACTION====');
			$return['status'] = false;
		}else{
		  
            // if table person, insert generated password
            if($table == 'person'){
                $salt = $CONFIG['default']['salt'];
                
                //this is the generated password
                $genPass = $this->generate_pass();
                
                //this is the encrypted password
                $password = sha1($genPass.$salt);
                
                //insert password id, password, salt
                $dataPass = array('id' => $insert['lastid'], 'password' => $password, 'salt' => $salt, 'username' => $data['username']);
                $insert_dataPas = $this->insertData('florakb_person',$dataPass,true);
                
                if ($insert_dataPas['status'] == 0){
        			$this->rollback();
        			logFile('====ROLLBACK TRANSACTION====');
        			$return['status'] = false;
        		}else{
                    $this->commit();
        			logFile('====COMMIT TRANSACTION====');
        			$return['status'] = true;
                    $return['lastid'] = $insert['lastid'];
        		}
            }else{
                $this->commit();
    			logFile('====COMMIT TRANSACTION====');
    			$return['status'] = true;
                $return['lastid'] = $insert['lastid'];
            }
		}
        
        return $return;
		exit;
    }
    
    /**
     * @todo generate a random password
     * 
     * @param $length = length of character
     * 
     * @return $result = random character
     * 
     * */
    function generate_pass($length = 8){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
    
        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
    
        return $result;
    }
    
    /**
     * @todo get list location
     * 
     * @return sql result
     * 
     * */
    function list_locn(){
        $sql = "SELECT id, locality FROM locn";
		$res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo get list person
     * 
     * @return sql result
     * 
     * */
    function list_person(){
        $sql = "SELECT id, name, email FROM person";
		$res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo get list person
     * 
     * @return sql result
     * 
     * */
    function list_taxon(){
        $sql = "SELECT id, fam, gen, sp FROM taxon";
		$res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo get enum of confid field in table det
     * @param $table = table name
     * @param $field = field name
     * 
     * @return preg match of sql result
     * 
     * */
    function get_enum($table, $field){
        $sql = "SHOW COLUMNS FROM `$table` WHERE Field = '$field'";
		$res = $this->fetch($sql,0);
        
        preg_match('/^enum\((.*)\)$/', $res['Type'], $matches);
        foreach( explode(',', $matches[1]) as $value )
        {
             $enum[] = trim( $value, "'" );
        }
        return $enum;
    }
}

?>