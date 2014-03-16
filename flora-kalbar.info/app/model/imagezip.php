<?php

class imagezip extends Database {

	var $configkey = "default";
	
	function insertImage($personID, $data){
        pr($data);
        $sql = "INSERT INTO img (id, name, email, twitter, website, phone, short_namecode) VALUES ('','{$data[0]['name']}','{$data[0]['email']}',$dataTwitter,$dataWeb,$dataPhone, '{$data[0]['shortName']}')";
		$res = $this->query($sql,0);
        return $res;
	}
    
    /**
     * @todo get id of a user from database
     * 
     * @param username = short name code from user input
     * @return result sql
     * 
     * */
    function validateUser($username){
        $sql = "SELECT id from person WHERE short_namecode= '$username'";
		$res = $this->fetch($sql,0);
        return $res;
    }
}

?>