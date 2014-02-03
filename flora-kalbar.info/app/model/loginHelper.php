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
		if($data== false) return false;
		
		$salt = '12345678PnD';
		$password = sha1($data['password'].$salt);
		$sql = "INSERT INTO login (username, password) VALUES ('{$data['username']}', '{$password}')";
		$res = $this->query($sql);
		
		if ($res)return true;
		return false;
		
		
	}
	
	function setSession($data=false)
	{
		
	}
}
?>