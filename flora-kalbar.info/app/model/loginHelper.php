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
	
    /**
     * @todo insert data user into person and florakb_person
     * 
     * @return boolean
     */
	function createUser($data=false)
	{
		if($data==false) return false;
		global $CONFIG;
		$salt = $CONFIG['default']['salt'];
		$password = sha1($data['password'].$salt);
        //echo $password.' = '.$data[0]['password'].' + '.$salt;
		$startTransaction = $this->begin();
		if (!$startTransaction) return false;
		
        
        if (empty($data['twitter'])){
            $dataTwitter = 'NULL';
        }else{
            $dataTwitter = "'".$data['twitter']."'";
        }
        
        if (empty($data['website'])){
            $dataWeb = 'NULL';
        }else{
            $dataWeb = "'".$data['website']."'";
        }
        
        if (empty($data['phone'])){
            $dataPhone = 'NULL';
        }else{
            $dataPhone = "'".$data['phone']."'";
        }
        
		$sql = "INSERT INTO person (id, name, email, twitter, website, phone) VALUES ('','{$data['name']}','{$data['email']}',$dataTwitter,$dataWeb,$dataPhone)";
		$res = $this->query($sql,0);
        
        $getID = "SELECT id from person WHERE email= '".$data['email']."' ";
		$resID = $this->fetch($getID,0);
        $sql2 = "INSERT INTO florakb_person (id, password, salt) VALUES ('{$resID['id']}','{$password}','{$salt}')";
		$res2 = $this->query($sql2,1);
		
        if ($res && $res2){
			$this->commit();
			logFile('==success create user==');
			return true;
		}
		
		$this->rollback();
		logFile('==ROLLBACK || failed create user==');
		return false;
	}
    
    /**
     * @todo check if name of user exist or not
     * 
     * @param $data = inputted name
     * @return boolean
     */
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
    
    /**
     * @todo check if email of user exist or not
     * 
     * @param $data = inputted email
     * @return boolean
     */
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
    
    /**
     * @todo check if twitter of user exist or not
     * 
     * @param $data = inputted twitter
     * @return boolean
     */
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
    
    /**
     * @todo get ID user/person to verificate login process
     * 
     * @param $data = email and password 
     */
    function getIdUser($data=false)
    {
        if($data==false) return false;
        //Select email to get ID
        $sql = "SELECT * FROM `person` WHERE `email` = '".$data['email']."' ";
        $res = $this->fetch($sql,0);
        if(empty($res)){return false;}
        return $res;
     }
     
     /**
     * @todo check password only
     * 
     * @param ID from db person
     * @param password inputted to validate
     */
    function checkPassword($data=false,$dataPassword){
        //select salt from ID
        $sql = "SELECT salt,password FROM `florakb_person` WHERE `id` = '".$data['id']."' ";
        $res = $this->fetch($sql,1,1);
        
        //match email and password
        $salt = $res[0]['salt']; 
        $password = sha1($dataPassword."$salt");
        if(count($data['id'])==1 && $res[0]['password']==$password){
            $result = array();
            $user = array();
            $user[]= array('id'=>$data['id'], 'name'=>$data['name'], 'email'=>$data['email'], 'twitter'=>$data['twitter'], 'website'=>$data['website'], 'phone'=>$data['phone'], 'short_namecode'=>$data['short_namecode']);
            $result[] = array('message'=>'success','user'=>$user);
            return $result;
        }
        else{
            $result[] = array('message'=>'error','user'=>'');
            return $result;
        }
    }
	
    /**
     * @todo create session after success login
     * 
     * @param $data = userdata(id,name,email,twitter,website,phone,short_namecode)
     */
	function setSession($data=false)
	{
        if($data==false) return false;
		// store session data
        $dataSession = array(
                'id' => $data[0]['id'],
                'name' => $data[0]['name'],
                'email' => $data[0]['email'],
                'twitter' => $data[0]['twitter'],
                'website' => $data[0]['website'],
                'phone' => $data[0]['phone'],
                'short_namecode' => $data[0]['short_namecode']
            );
        $_SESSION['login'] = $dataSession;
	}
    
    /**
     * @todo destroy session
     * 
     */
    function logoutUser()
    {
        session_destroy();
        global $basedomain;  
        header( 'Location: '.$basedomain ) ;  
    }
}
?>