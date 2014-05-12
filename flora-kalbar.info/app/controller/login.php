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
        //$this->models = $this->loadModel('frontend');
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
        $getUserdata = $this->loginHelper->getUserdata($data);
        $getUserappdata = $this->loginHelper->getUserappdata($getUserdata['id']);
        
        if(count($getUserdata['id'])==1){
            $checkPassword = $this->loginHelper->checkPassword($getUserdata,$data['password']);
            if($checkPassword){
                echo json_encode('success');
                $data = array();
                $data[] = array('person'=>$getUserdata,'person_app'=>$getUserappdata);
                $startSession = $this->loginHelper->setSession($data);
            }
            else{
                echo json_encode('error');
            }
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
