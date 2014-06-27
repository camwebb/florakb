<?php

class login extends Controller {
	
	var $models = FALSE;
	var $view;

	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
    }
	
	function loadmodule()
	{
        $this->userHelper = $this->loadModel('userHelper');
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->activityHelper = $this->loadModel('activityHelper');
        
	}
	
	function index(){

        return $this->loadView('home');
    }
	
    /**
     * @todo create new user
     *           
     * @return $statusName and $msgName = status and message for validating name
     * @return $statusEmail and $msgEmail = status and message for validating email
     * @return $statusUsername and $msgUsername = status and message for validating username
     * @return $statusTwitter and $msgTwitter = status and message for validating twitter                  
     */    
    function doSignup(){
        
        global $CONFIG;
        $data = $_POST;
        
        $checkEmail = $this->loginHelper->checkEmail($data['email']); 
        $checkUsername = $this->loginHelper->checkUsername($data['username']);       
        $checkTwitter = $this->loginHelper->checkTwitter($data['twitter']);
        
        if($checkEmail !== true || $checkUsername !== true || $checkTwitter !== true){
            $statusEmail = "";
            $msgEmail = "";
            $statusUsername = "";
            $msgUsername = "";
            $statusTwitter = "";
            $msgTwitter = "";
            
            if($checkEmail !== true){
                $statusEmail = "exist";
                $msgEmail = "Email already exist";
            }
            if($checkUsername !== true){
                $statusUsername = "exist";
                $msgUsername = "Username already exist";
            }
            if($checkTwitter !== true){
                $statusTwitter = "exist";
                $msgTwitter = "Twitter already exist";
            }
                echo json_encode(array('statusEmail' => $statusEmail, 'msgEmail' => $msgEmail, 'statusUsername' => $statusUsername, 'msgUsername' => $msgUsername, 'statusTwitter' => $statusTwitter, 'msgTwitter' => $msgTwitter));
                exit;
        }
        // else{
        //     echo json_encode(array('status' => 'error'));
        //     exit;
        // }
        
        if($checkEmail && $checkUsername && $checkTwitter){

        
            $signup = $this->loginHelper->createUser($data);
            if ($signup){

                // send mail before activate account
                $dataArr['email'] = $data['email'];
                $dataArr['username'] = $data['username'];
                $dataArr['token'] = sha1('register'.$data['email']);
                $dataArr['validby'] = sha1(CODEKIR);
                $dataArr['regfrom'] = 1;

                $inflatData = encode(serialize($dataArr));
                logFile($inflatData);


                $to = $data['email'];
                $from = $CONFIG['email']['EMAIL_FROM_DEFAULT'];
                // $msg = "To activate your account please <a href='{$basedomain}login/validate/?ref={$inflatData}'>click here</a>";
                $this->view->assign('encode',$inflatData);
                $msg = "<p>Hi ".$data['username']."!</p>";
                $msg .= $this->loadView('emailTemplate');
                // try to send mail 
                $sendMail = sendGlobalMail($to, $from, $msg,true);
                logFile('mail send '.serialize($sendMail));

                $this->activityHelper->updateEmailLog(false,$to,'account',1);

            }

            echo json_encode(array('test' => 'test'));
            exit;
        }
        exit;
    }
    
    /**
     * @todo enter the site as user
     */        
    function doLogin(){
        $data = $_POST;
        
        //query data
        $getUserappData = $this->userHelper->getUserappData('username',$data['username'],1);
        $getUserData = $this->userHelper->getUserData('id',$getUserappData['id']);
        
        $pwd = $data['password'];
        
        if(count($getUserData['id'])==1){
            $checkPassword = $this->loginHelper->checkPassword($getUserData,$pwd);
            if($checkPassword){
                echo json_encode('success');
                $data = array();
                $data[] = array('person'=>$getUserData,'person_app'=>$getUserappData);
                $startSession = $this->loginHelper->setSession($data, $pwd);
            }
            else{
                echo json_encode('error');
            }
        }
		else{
			echo json_encode('error');
		}
        exit; 
    }
    
    /**
     * @todo log out from site
     */
    function doLogout(){
        $logout = $this->loginHelper->logoutUser(); 
    }

    function validate()
    {

        $data = _g('ref');
        

        // exit;
        logFile($data);
        if ($data){

            $decode = unserialize(decode($data));
           
            // check if token is valid
           
            $salt = "register";
            $userMail = $decode['email'];
            $origToken = sha1($salt.$userMail);

            // pr($decode);
            if ($decode['token']==$origToken){
                // is valid, then create account and set status to validate

                if($decode['regfrom']==1){
                    
                    $this->view->assign('enterAccount',false);  
                    $updateAccount = $this->loginHelper->updateUserStatus($decode['username']);

                    if ($updateAccount){


                        createAccount($data);
                        logFile('account ftp user '.$decode['email']. ' created');

                        $this->view->assign('validate','Validate account success');
                        

                    }else{
                        
                        $this->view->assign('validate','Validate account error');
                        logFile('update n_status user '.$decode['email'].' failed');
                    }

                }else{

                    $this->view->assign('email',$decode['email']);
                    $this->view->assign('enterAccount',true);         
                    return $this->loadView('validateProfile');
                }

                

                
                

            }else{

                // invalid token
                $this->view->assign('validate','Validate account error');
                logFile('token mismatch');
                
            }

        }
        
        return $this->loadView('home');
    }

    function accountValid()
    {

        $token = _p('token');
        if ($token){

            $data['email'] = _p('email');
            $data['username'] = _p('username');
            $data['password'] = _p('newPassword');

            $updateAccount = $this->loginHelper->updateUserAccount($data);
            if ($updateAccount){

                // createAccount($data);
                logFile('account ftp user '.$data['email']. ' created');

                $this->view->assign('validate','Validate account success');
                
            }else{
                $this->view->assign('validate','Validate account error');
                logFile('update n_status user '.$data['email'].' failed');
            }
        }

        $this->view->assign('enterAccount',false);  
        return $this->loadView('home');
    }

          
}

?>
