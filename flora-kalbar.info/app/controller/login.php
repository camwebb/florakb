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
     * @return $statusTwitter and $msgTwitter = status and message for validating twitter                  
     */    
    function doSignup(){
        
        $name = $_POST["name"];
        $email = $_POST["email"];
        $twitter = $_POST["twitter"];
        $website = $_POST["website"];
        $phone = $_POST["phone"];
        $pass = $_POST["pass"];
        $re_pass = $_POST["re_pass"];
        
        $checkName = $this->loginHelper->checkName($name);       
        $checkEmail = $this->loginHelper->checkEmail($email);        
        $checkTwitter = $this->loginHelper->checkTwitter($twitter);
        
        if($checkName !== true || $checkEmail !== true || $checkTwitter !== true){
            $statusName = "";
            $msgName = "";
            $statusEmail = "";
            $msgEmail = "";
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
            if($checkTwitter !== true){
                $statusTwitter = "exist";
                $msgTwitter = "Twitter already exist";
            }
                echo json_encode(array('statusName' => $statusName, 'msgName' => $msgName, 'statusEmail' => $statusEmail, 'msgEmail' => $msgEmail, 'statusTwitter' => $statusTwitter, 'msgTwitter' => $msgTwitter));
                exit;
        }
        
        if($checkName && $checkEmail && $checkTwitter){
            $data = array();
            $data[]= array('name'=>$name, 'email'=>$email, 'twitter'=>$twitter, 'website'=>$website, 'phone'=>$phone, 'password'=>$pass);
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
        $email = $_POST["email"]; 
        $pass = $_POST["pass"];
        
        // To protect MySQL injection
        $email = stripslashes($email);
        $pass = stripslashes($pass);
        $email = mysql_real_escape_string($email);
        $pass = mysql_real_escape_string($pass); 
        
        //query data
        $data = array();
        $data[]= array('email'=>$email, 'password'=>$pass);
        $login = $this->loginHelper->loginUser($data); 
        $startSession = $this->loginHelper->setSession($login); 
    } 
    
    /**
     * @todo log out from site
     */
    function doLogout(){
        $logout = $this->loginHelper->logoutUser(); 
    }           
}

?>
