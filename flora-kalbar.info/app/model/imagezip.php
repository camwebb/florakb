<?php

class imagezip extends Database {

	var $configkey = "default";
	
	function insertImage($personID, $data){
	   
	}
    
    function validateUser($username){
        $sql = "SELECT id from person WHERE short_namecode= '$username'";
		$res = $this->fetch($sql,0);
        return $res;
    }
}

?>