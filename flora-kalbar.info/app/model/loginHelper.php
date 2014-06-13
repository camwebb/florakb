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
		global $CONFIG, $basedomain;
		
		$host = $CONFIG['default']['hostname'];
		$port = "12345";
		
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
        
		$sql = "INSERT INTO person (name, email, twitter, website, phone) VALUES ('{$data['name']}','{$data['email']}',$dataTwitter,$dataWeb,$dataPhone)";
		$res = $this->query($sql,0);
        
        $getID = "SELECT id from person WHERE email= '".$data['email']."' ";
		$resID = $this->fetch($getID,0);
        $sql2 = "INSERT INTO florakb_person (id, password, username, salt) VALUES ('{$resID['id']}','{$password}','{$data['username']}','{$salt}')";
		$res2 = $this->query($sql2,1);
		
        if ($res && $res2){

            // send mail before activate account
            $dataArr['email'] = $data['email'];
            $dataArr['username'] = $data['username'];
            $dataArr['token'] = sha1('register'.$data['email']);
            $dataArr['validby'] = sha1(CODEKIR);

            $inflatData = encode(serialize($dataArr));
            logFile($inflatData);


            $to = $data['email'];
            $from = $CONFIG['email']['EMAIL_FROM_DEFAULT'];
            $msg = "To activate your account please <a href='{$basedomain}login/validate/?ref={$inflatData}'>click here</a>";
            // try to send mail 
            $sendMail = sendGlobalMail($to, $from, $msg,false);
            logFile('mail send '.$sendMail);

            if ($sendMail['result']){

                $this->commit();
                logFile('==success create user==');
                return true;
            } 
            else $this->rollback();
            // createAccount($data);
			// exec("echo '".$data['username']. " ".$data['password']."' | nc ".$host." ".$port);
			
			// logFile("echo '".$data['username']. " ".$data['password']."' | nc ".$host." ".$port);
			
			
			return false;
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
     * @todo check if username of user exist or not
     * 
     * @param $data = inputted username
     * @return boolean
     */
    function checkUsername($data=false)
    {
        if($data==false) return false;
        $sql = "SELECT COUNT(`id`) AS total FROM `florakb_person` WHERE `username` = '".$data."' ";
        $res = $this->fetch($sql,0,1);
        
        if ($res['total'] > 0){
            logFile('username EXIST/');
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
        if($data==''){return true;}
        else{
            $sql = "SELECT COUNT(`id`) AS total FROM `person` WHERE `twitter` = '".$data."' ";
            $res = $this->fetch($sql,0);
            
            if ($res['total'] > 0){
                logFile('Twitter EXIST/');
                return false;
            }
        }
        return true;
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
        $passwordDB = sha1($dataPassword."$salt");
        $password = $res[0]['password'];
        if($passwordDB==$password){return TRUE;}
        return FALSE;
    }
	
    /**
     * @todo create session after success login
     * 
     * @param $data = userdata(id,name,email,twitter,website,phone)
     */
	function setSession($data=false, $password=false)
	{

        $session = new Session;

        if($data==false && $password==false) return false;
		// store session data
        $dataSession = array(
                'id' => $data[0]['person']['id'],
                'name' => $data[0]['person']['name'],
                'email' => $data[0]['person']['email'],
                'username' => $data[0]['person_app']['username'],
                'project' => $data[0]['person']['project'],
                'institutions' => $data[0]['person']['institutions'],
                'twitter' => $data[0]['person']['twitter'],
                'website' => $data[0]['person']['website'],
                'phone' => $data[0]['person']['phone'],
                'password' => $password
            );
        // $_SESSION['login'] = $dataSession;

        // set session, parameternya (data sessi, nama sessinya)
        $session->set_session($dataSession, 'login');
	}
    
    /**
     * @todo destroy session
     * 
     */

    function updateUserStatus($username=false)
    {
        if (!$username) return false;
        $sql = "UPDATE florakb_person SET n_status = 1 WHERE username = '{$username}' LIMIT 1";
        $res = $this->query($sql,1);
        if($res) return true;
        return false;
    }

    function logoutUser()
    {
        session_destroy();
        global $basedomain;  
        header( 'Location: '.$basedomain ) ;  
    }
}
?>