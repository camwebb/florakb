<?php
class userHelper extends Database {
	
    function editProfile($data=false){
        if($data==false) return false;
        $sql = "UPDATE `person` SET `name` = '".$data['name']."', `email` = '".$data['email']."', `twitter` = '".$data['twitter']."', `website` = '".$data['website']."', `phone` = '".$data['phone']."', `short_namecode` = '".$data['short_namecode']."' WHERE `id` = '".$_SESSION['login']['id']."' ";
        $res = $this->query($sql,0);
        if($res){return true;}
    }
    
    function getUserData($field,$data){
        if($data==false) return false;
        $sql = "SELECT * FROM `person` WHERE `$field` = '".$data."' ";
        $res = $this->fetch($sql,0);  
        return $res; 
    }
}
?>