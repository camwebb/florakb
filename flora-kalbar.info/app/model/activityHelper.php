<?php
class activityHelper extends Database {

	/* generate reference query */

	var $user = null;
	function __construct()
	{

		$session = new Session;
		$getSessi = $session->get_session();
		$this->user = $getSessi['login'];

	}

	function generateEmail($email=false, $username=false)
	{
		global $CONFIG;

		if (!$email && !$username) return false;

		$dataArr['email'] = $email;
        $dataArr['username'] = $username;
        $dataArr['token'] = sha1('register'.$email);
        $dataArr['validby'] = sha1(CODEKIR);

        $inflatData = encode(serialize($dataArr));
        logFile($inflatData);


        $return['to'] = $email;
        $return['from'] = $CONFIG['email']['EMAIL_FROM_DEFAULT'];
        $return['subject'] = "[NOTIFICATION]";
        $return['msg'] = "To activate your account please <a href='{$basedomain}login/validate/?ref={$inflatData}'>click here</a>";
            
        return $return;
	}

	function emailLog($email=false, $subject='account')
	{
		if (!$email) return false;

		$sql = "SELECT COUNT(1) AS total FROM florakb_mail_log WHERE receipt = '{$email}' 
				AND subject = '{$subject}' AND n_status = 1";
		// pr($sql);
		$res = $this->fetch($sql,1);
		if ($res['total']>0) return true; // true if exist
		return false;
	}

	function updateEmailLog($update=true, $receipt=false, $subject='account', $n_status=0)
	{
		
        $date = date('Y-m-d H:i:s');

        if ($update){
        	
        	$sql = "UPDATE `florakb_mail_log` SET  n_status = {$n_status} 
        			WHERE receipt = '{$receipt}' AND subject = '{$subject}' AND n_status = 0 LIMIT 1";
	        // pr($sql);
	        $res = $this->query($sql,1);  
	        if ($res) return true;
	        return false;

        }else{
        	$sql = "INSERT INTO `florakb_mail_log` (receipt, subject, send_date, n_status) 
	                VALUES ('{$receipt}', '{$subject}', '{$date}', {$n_status})";
	        // pr($sql);
	        $res = $this->query($sql,1);  
	        if ($res) return true;
	        return false;
        }
        
	}

	function storeUserUploadLog($data=null, $filename=null)
    {

        $userid = $this->user['id'];
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `florakb_upload_log` (userid, filename, `desc`, upload_date) 
                VALUES ({$userid}, '{$filename}', '{$data}', '{$date}')";
        // pr($sql);
        $res = $this->query($sql,1);  
        if ($res) return true;
        return false;
    }

}

?>


