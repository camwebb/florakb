<?php

class imagezip extends Database {

	var $configkey = "default";
	
	function insertImage($personID, $data){
        $sql = "UPDATE img SET md5sum = '$data[md5sum]', directory = '$data[directory]', mimetype = '$data[mimetype]' WHERE filename = '$data[filename]' AND personID = '$personID'";
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