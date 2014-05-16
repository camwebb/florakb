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
	}
	
	function index(){
    	//return $this->loadView('home');
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
        
        $data = $_POST;
        
        $checkName = $this->loginHelper->checkName($data['name']);       
        $checkEmail = $this->loginHelper->checkEmail($data['email']); 
        $checkUsername = $this->loginHelper->checkUsername($data['username']);       
        $checkTwitter = $this->loginHelper->checkTwitter($data['twitter']);
        
        if($checkName !== true || $checkEmail !== true || $checkUsername !== true || $checkTwitter !== true){
            $statusName = "";
            $msgName = "";
            $statusEmail = "";
            $msgEmail = "";
            $statusUsername = "";
            $msgUsername = "";
            $statusTwitter = "";
            $msgTwitter = "";
            
            if($checkName !== true){
                $statusName = "exist";
                $msgName = "Name already exist";
            }
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
                echo json_encode(array('statusName' => $statusName, 'msgName' => $msgName, 'statusEmail' => $statusEmail, 'msgEmail' => $msgEmail, 'statusUsername' => $statusUsername, 'msgUsername' => $msgUsername, 'statusTwitter' => $statusTwitter, 'msgTwitter' => $msgTwitter));
                exit;
        }
        
        if($checkName && $checkEmail && $checkUsername && $checkTwitter){
            $signup = $this->loginHelper->createUser($data);
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
        $getUserData = $this->userHelper->getUserData('email',$data['email']);
        $getUserappData = $this->userHelper->getUserappData('id',$getUserData['id']);
        
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
}

?>
