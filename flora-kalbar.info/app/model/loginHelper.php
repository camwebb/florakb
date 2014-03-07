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
		$sql = "INSERT INTO person (id, name, email, twitter, website, phone) VALUES ('','{$data[0]['name']}','{$data[0]['email']}','{$data[0]['twitter']}','{$data[0]['website']}','{$data[0]['phone']}')";
		$res = $this->query($sql,0);
        $sql2 = "INSERT INTO florakb_person (id, password, salt) VALUES ('','{$password}','{$salt}')";
		$res2 = $this->query($sql2,1);
		
        if ($res && $res2)return true;
		return false;
		
		
	}
	
	function setSession($data=false)
	{
		
	}
}
?>