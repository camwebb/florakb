<?php

class loginHelper extends Database {
	

	function local($data=false)
	{
		if($data== false) return false;
		
		$salt = '12345678PnD';
		$password = sha1($data['password'].$salt);
		$sql = "SELECT * FROM login where username = '{$data['username']}' AND password = '{$password}' LIMIT 1";
		$res = $this->fetch($sql, 0);
		// pr($sql);
		if ($res) return $res;
		return false;
	}
	
	function createUser($data=false)
	{
		if($data==false) return false;
		
		$salt = '12345678PnD';
		$password = sha1($data[0]['password'].$salt);
		$startTransaction = $this->begin();
		if (!$startTransaction) return false;
		
        
        if (empty($data[0]['twitter'])){
            $dataTwitter = 'NULL';
        }else{
            $dataTwitter = "'".$data[0]['twitter']."'";
        }
        
        if (empty($data[0]['website'])){
            $dataWeb = 'NULL';
        }else{
            $dataWeb = "'".$data[0]['website']."'";
        }
        
        if (empty($data[0]['phone'])){
            $dataPhone = 'NULL';
        }else{
            $dataPhone = "'".$data[0]['phone']."'";
        }
        
		$sql = "INSERT INTO person (id, name, email, twitter, website, phone, short_namecode) VALUES ('','{$data[0]['name']}','{$data[0]['email']}',$dataTwitter,$dataWeb,$dataPhone, '{$data[0]['shortName']}')";
		$res = $this->query($sql,0);
        
        $getID = "SELECT id from person WHERE email= '".$data[0]['email']."' AND short_namecode ='".$data[0]['shortName']."' ";
		$resID = $this->fetch($getID,0);
        $sql2 = "INSERT INTO florakb_person (id, password, salt) VALUES ('{$resID['id']}','{$password}','{$salt}')";
		$res2 = $this->query($sql2,1);
		
        if ($res && $res2){
			$this->commit();
			logFile('success create user');
			return true;
		}
		
		$this->rollback();
		logFile('failed create user');
		return false;
	}
    
    function checkName($data=false)
    {
        if($data==false) return false;
        $sql = "SELECT COUNT(`id`) AS total FROM `person` WHERE `name` = '".$data."' ";
        $res = $this->fetch($sql,0);
        
        if ($res['total'] > 0){
            logFile('Name EXIST/');
            return false;
        }
        return true;
    }
    
    function checkEmail($data=false)
    {
        if($data==false) return false;
        $sql = "SELECT COUNT(`id`) AS total FROM `person` WHERE `email` = '".$data."' ";
        $res = $this->fetch($sql,0);
        
        if ($res['total'] > 0){
            logFile('Email EXIST/');
            return false;
        }
        return true;
    }
    
    function checkTwitter($data)
    {
        $sql = "SELECT COUNT(`id`) AS total FROM `person` WHERE `twitter` = '".$data."' ";
        $res = $this->fetch($sql,0);
        
        if ($res['total'] > 0){
            logFile('Twitter EXIST/');
            return false;
        }
        return true;
    }
    
    function checkShortName($data=false)
    {
        if($data==false) return false;
        $sql = "SELECT COUNT(`id`) AS total FROM `person` WHERE `short_namecode` = '".$data."' ";
        $res = $this->fetch($sql,0);
        
        if ($res['total'] > 0){
            logFile('Shortname EXIST/');
            return false;
        }
        return true;
    }
	
	function setSession($data=false)
	{
		
	}
}
?>