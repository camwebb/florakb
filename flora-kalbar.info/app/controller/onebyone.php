<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

//------------------------------------------------------------------------------
// A session is required for the messages to work
//------------------------------------------------------------------------------
if( !session_id() ) session_start();
if(!$_SESSION){
    header('Location: '.$basedomain);
}
class onebyone extends Controller {
	
	var $models = FALSE;
	var $view;
    
    /**
     * @todo asign variable basedomain, call function load module
     * 
     * */
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
        $this->msg = new Messages();
	}
    
    /**
     * @todo load database module
     * 
     * */
	public function loadmodule()
	{
        $this->insertonebyone = $this->loadModel('insertonebyone');
        
        //only used for check name, twitter, and email
        $this->loginHelper = $this->loadModel('loginHelper');
        
        //used for update image data and validate email input in insertImage function
        $this->imagezip = $this->loadModel('imagezip');
	}
	
    /**
     * @todo load master view onebyone
     * 
     * */
	public function index(){
        $this->view->assign('msg', '');        	   
		return $this->loadView('formContentIndiv');
	}
    
    /**
     * @todo show view for individu and location form
     * */
    public function indivContent(){
        $session = new Session;
        
        $sess_user = $session->get_session();
        $sess_data = $sess_user['ses_user'];
        
        if(isset($sess_data['onebyone'])){
            $session->delete_session('onebyone');
            $session->delete_session('image_sess');
        }
        
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        //get list location
        $listlocn = $this->insertonebyone->list_locn();
        $this->view->assign('locn', $listlocn);
        
        return $this->loadView('formContentIndiv');
    }
    
    /**
     * @todo show view for determinant, taxon, and person form
     * */
    public function detContent(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        //get list person
        $listPerson = $this->insertonebyone->list_person();
        $this->view->assign('person', $listPerson);
        
        //get list taxon
        $listTaxon = $this->insertonebyone->list_taxon();
        $this->view->assign('taxon', $listTaxon);
        
        //get list enum confid
        $confid_enum = $this->insertonebyone->get_enum('det','confid');
        $this->view->assign('confid_enum', $confid_enum);
        
        return $this->loadView('formContentDet');
    }
    
    /**
     * @todo show view for image form
     * */
    public function imageContent(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        
        $session = new Session;
        $image_sess = $session->get_session();
        if(isset($image_sess['ses_user']['image_sess'])){
            $this->view->assign('image_sess', $image_sess['ses_user']['image_sess']);
        }
        
        //get plantpart enum
        $plantpart_enum = $this->insertonebyone->get_enum('img','plantpart');
        $this->view->assign('plantpart_enum', $plantpart_enum);
        
        return $this->loadView('formContentImage');
    }
    
    /**
     * @todo insert person from posted data
     * */
    public function insertPerson(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('person',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Person Success');
        }else{
            $this->msg->add('e', 'Update Person Failed');
        }
        header('Location: ../onebyone/detContent');
    }
    
    /**
     * @todo insert location from posted data
     * */
    public function insertLocation(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('locn',$data);
        
        /*
        //manual submission form
        if($insertData){
            $this->msg->add('s', 'Update Location Success');
        }else{
            $this->msg->add('e', 'Update Location Failed');
        }
        header('Location: ../onebyone/indivContent');*/
        
        //ajax form
        if($insertData){
            echo 'success';
        }else{
            echo 'error';
        }            
        exit;
    }
    
    /**
     * @todo insert individu from posted data
     * */
    public function insertIndiv(){
        $data = $_POST;
        
        //get data user from session
        $session = new Session;
        $login = $session->get_session();
        $userData = $login['ses_user'];
        
        $personID = $userData['login']['id'];
        $data['personID'] = $personID;
        
        $insertData = $this->insertonebyone->insertTransaction('indiv',$data);
        $sess_onebyone = array('indivID' => $insertData['lastid']);
        $session->set_session($sess_onebyone,'onebyone');
        
        if($insertData){
            $this->msg->add('s', 'Update Individu Success');
        }else{
            $this->msg->add('e', 'Update Individu Failed');
        }
        
        header('Location: ../onebyone/detContent');
    }
    
    /**
     * @todo insert individu from posted data
     * */
    public function insertDet(){
        $data = $_POST;
        
        //get data user from session
        $session = new Session;
        $login = $session->get_session();
        $userData = $login['ses_user'];
        
        $indivID = $userData['onebyone']['indivID'];
        
        $data['indivID'] = $indivID;
        $data['det_date'] = date("Y-m-d");
        
        $insertData = $this->insertonebyone->insertTransaction('det',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Determinant Success');
        }else{
            $this->msg->add('e', 'Update Determinant Failed');
        }
        header('Location: ../onebyone/imageContent');
    }
    
    /**
     * @todo insert taxon from posted data
     * */
    public function insertTaxon(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('taxon',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Taxon Success');
        }else{
            $this->msg->add('e', 'Update Taxon Failed');
        }
        header('Location: ../onebyone/detContent');
    }
    
    /**
     * @todo insert image from posted data
     * */
    public function insertImage(){
        global $CONFIG;
        $data = $_POST;
        //pr($data);exit;
        
        $name = 'filename';
        $path = '';
        
        $uploaded_file = uploadFile($name, $path, 'image');
        
        //if uploaded
        if($uploaded_file['status'] != '0'){
            //validate email and get short_namecode
            /*$validateEmail = $this->validateEmail($data['email']);
            if($validateEmail['status'] != 'success'){
                $this->msg->add('e', 'Email validation Failed');
                header('Location: ../onebyone/image');
                exit;
            }
            
            $personID = $validateEmail['personID'];
            $username = $validateEmail['short_namecode'];*/
            
            
            $session = new Session;
            $login = $session->get_session();
            $userData = $login['ses_user'];
            
            $username = $userData['login']['username'];
            $personID = $userData['login']['id'];
            $indivID = $userData['onebyone']['indivID'];
        
            $tmp_name = $uploaded_file['full_name'];
            $entry = str_replace(array('\'', '"'), '', $uploaded_file['real_name']);
            //$entry = $uploaded_file['real_name'];
            $image_name_encrypt = md5($entry);
            
            //check filename
            $dataExist = $this->imagezip->dataExist($personID, $entry);            
            
            $path_entry = $CONFIG['default']['upload_path'];
            $src_tmp = $path_entry."/".$tmp_name;
            
            if(!$dataExist){
                $path_data = 'public_assets/';
                //$path_user = $path_data.$username;
                $path_img = $path_data.'/img';
                $path_img_1000px = $path_img.'/1000px';
                $path_img_500px = $path_img.'/500px';
                $path_img_100px = $path_img.'/100px';
                
                $fileinfo = getimagesize($path_entry.'/'.$tmp_name);
                
                $toCreate = array($path_img, $path_img_1000px, $path_img_500px, $path_img_100px);
                createFolder($toCreate, 0755);
                
                copy($path_entry."/".$tmp_name, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg');
                if(!@ copy($path_entry."/".$tmp_name, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg')){
                    $status = "error";
                    $msg= error_get_last();
                }
                else{
                    $dest_1000px = $CONFIG['default']['root_path'].'/'.$path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg';
                    $dest_500px = $CONFIG['default']['root_path'].'/'.$path_img_500px.'/'.$image_name_encrypt.'.500px.jpg';
                    $dest_100px = $CONFIG['default']['root_path'].'/'.$path_img_100px.'/'.$image_name_encrypt.'.100px.jpg';
                    
                    if ($fileinfo[0] >= 1000 || $fileinfo[1] >= 1000 ) {
                        if ($fileinfo[0] > $fileinfo[1]) {
                            $percentage = (1000/$fileinfo[0]);
                            $config['width'] = $percentage*$fileinfo[0];
                            $config['height'] = $percentage*$fileinfo[1];
                        }else{
                            $percentage = (1000/$fileinfo[1]);
                            $config['width'] = $percentage*$fileinfo[0];
                            $config['height'] = $percentage*$fileinfo[1];
                        }
                        
                        $this->resize_pic($src_tmp, $dest_1000px, $config);
                        unset($config);
                    }
                    
                    //Set cropping for y or x axis, depending on image orientation
                    if ($fileinfo[0] > $fileinfo[1]) {
                        $config['width'] = $fileinfo[1];
                        $config['height'] = $fileinfo[1];
                        $config['x_axis'] = (($fileinfo[0] / 2) - ($config['width'] / 2));
                        $config['y_axis'] = 0;
                    }
                    else {
                        $config['width'] = $fileinfo[0];
                        $config['height'] = $fileinfo[0];
                        $config['x_axis'] = 0;
                        $config['y_axis'] = (($fileinfo[1] / 2) - ($config['height'] / 2));
                    }

                    $this->cropToSquare($src_tmp, $dest_500px, $config);
                    unset($config);
                    
                    //set new config
                    $config['width'] = 500;
                    $config['height'] = 500;
                    $this->resize_pic($dest_500px, $dest_500px, $config);
                    unset($config);
                    
                    $config['width'] = 100;
                    $config['height'] = 100;
                    $this->resize_pic($dest_500px, $dest_100px, $config);
                    unset($config);
                    
                    //add file information to array
                    /*$fileToInsert = array('filename' => $entry,'md5sum' => $image_name_encrypt, 'directory' => '', 'mimetype' => $fileinfo['mime']);
                    
                    $insertImage = $this->imagezip->updateImage($personID, $fileToInsert);*/
                    
                    $data['filename'] = $entry;
                    $data['md5sum'] = $image_name_encrypt;
                    $data['mimetype'] = $fileinfo['mime'];
                    $data['indivID'] = $indivID;
                    $data['personID'] = $personID;
                    
                    $insertData = $this->insertonebyone->insertTransaction('img',$data);
                    
                    if($insertData){
                        $this->msg->add('s', 'Update image success');
                        $session = new Session;
                        
                        $dataSession = array();
                        
                        $sess_image = $session->get_session();
                        $sess_user = $sess_image['ses_user'];
                        if(isset($sess_user['image_sess'])){
                            foreach ($sess_user['image_sess'] as $data_before){
                                array_push($dataSession,$data_before);
                            }
                        }
                        array_push($dataSession, $data);
                        $session->set_session($dataSession,'image_sess');
                        //$session->delete_session('onebyone');
                    }else{
                        $this->msg->add('e', 'Update image failed');
                    }
                } // end if copy
                
            }else{
                $this->msg->add('e', 'Image exist');
            }
            unlink($src_tmp);
        }else{
            $this->msg->add('e', $uploaded_file['message']);
        }
        header('Location: ../onebyone/imageContent');
    }
    
    /**
     * @todo check input name exist from input
     * 
     * @return boolean true/false
     * */
    public function check_Name(){
        $data = $_POST['name'];
        $check = $this->loginHelper->checkName($data);
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
    
    /**
     * @todo check input twitter exist from input
     * 
     * @return boolean true/false
     * */
    public function check_Twitter(){
        $data = $_POST['twitter'];
        $check = $this->loginHelper->checkTwitter($data);
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
    
    /**
     * @todo check input email exist from input
     * 
     * @return boolean true/false
     * */
    public function check_Email(){
        $return = false;
    
        $data = $_POST['email'];
        $check = $this->loginHelper->checkEmail($data);
        
        if($check){
            $return = true;
        }else{
            $return = false;
        }
        echo $return;
        exit;
    }
    
    /**
     * @todo get id and shortname of user
     * 
     * @param email = email from user input
     * @return status = a status of success/error validate
     * @return short_namecode = short name of user (if success)
     * @return personID = id for person (if success)
     * 
     * */
    function validateEmail($email){
        $validate = $this->imagezip->validateEmail($email);
        if($validate['id'] != ''){
            $return = array('status' => "success", 'short_namecode' => $validate['short_namecode'], 'personID' => $validate['id']);
        }else{
            $return = array('status' => "error", 'short_namecode' => $validate['short_namecode'], 'personID' => $validate['id']);
        }  
        return $return;
        exit;
    }
    
    /**
     * @todo crop image to square from center
     * 
     * @param string $src = full image path with file name
     * @param string $dest = path destination for new image
     * @param array $config = array contain configuration to crop image
     * 
     * @param int $config['width']
     * @param int $config['height']
     * @param int $config['x_axis']
     * @param int $config['y_axis']
     * 
     * @return bool Returns TRUE on success, FALSE on failure
     * 
     * */
    function cropToSquare($src, $dest, $config){
        list($current_width, $current_height) = getimagesize($src);
        $canvas = imagecreatetruecolor($config['width'], $config['height']);
        $current_image = imagecreatefromjpeg($src);
        if (!@ imagecopy($canvas, $current_image, 0, 0, $config['x_axis'], $config['y_axis'], $current_width, $current_height)){
            return false;
        }else{
            if (!@ imagejpeg($canvas, $dest, 100)){
                return false;
            }else{
                return true;
            }
        }
    }
    
    /**
     * @todo resize image
     * 
     * @param string $src = full image path with file name
     * @param string $dest = path destination for new image
     * @param array $config = array contain configuration to crop image
     * 
     * @param int $config['width']
     * @param int $config['height']
     * 
     * @return bool Returns TRUE on success, FALSE on failure
     * 
     * */
    function resize_pic($src, $dest, $config){
        list($current_width, $current_height) = getimagesize($src);
        $canvas = imagecreatetruecolor($config['width'], $config['height']);
        $current_image = imagecreatefromjpeg($src);
        
        // Resize
        if (!@ imagecopyresized($canvas, $current_image, 0, 0, 0, 0, $config['width'], $config['height'], $current_width, $current_height)){
            return false;
        }else{
            // Output
            if (!@ imagejpeg($canvas, $dest, 100)){
                return false;
            }else{
                return true;
            }
        }
    }
}

?>
