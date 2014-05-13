<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

//------------------------------------------------------------------------------
// A session is required for the messages to work
//------------------------------------------------------------------------------
if( !session_id() ) session_start();

class onebyone extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
        $this->msg = new Messages();
	}
	public function loadmodule()
	{
        $this->insertonebyone = $this->loadModel('insertonebyone');
        
        //only used for check name, twitter, and email
        $this->loginHelper = $this->loadModel('loginHelper');
        
        //used for update image data and validate email input in insertImage function
        $this->imagezip = $this->loadModel('imagezip');
	}
	
	public function index(){
		return $this->loadView('onebyone');
	}
    
    /**
     * @todo show view for location form
     * */
    public function location(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formLocation');
    }
    
    /**
     * @todo show view for person form
     * */
    public function person(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formPerson');
    }
    
    /**
     * @todo show view for taxon form
     * */
    public function taxon(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formTaxon');
    }
    
    /**
     * @todo show view for image form
     * */
    public function image(){
        $msg = $this->msg->display('all', false);
        $this->view->assign('msg', $msg);
        return $this->loadView('formImage');
    }
    
    /**
     * @todo insert person from posted data
     * */
    public function insertPerson(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('person',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Success');
        }else{
            $this->msg->add('e', 'Update Failed');
        }
        header('Location: ../onebyone/person');
    }
    
    /**
     * @todo insert location from posted data
     * */
    public function insertLocation(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('locn',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Success');
        }else{
            $this->msg->add('e', 'Update Failed');
        }
        header('Location: ../onebyone/location');
    }
    
    /**
     * @todo insert taxon from posted data
     * */
    public function insertTaxon(){
        $data = $_POST;
        $insertData = $this->insertonebyone->insertTransaction('taxon',$data);
        
        if($insertData){
            $this->msg->add('s', 'Update Success');
        }else{
            $this->msg->add('e', 'Update Failed');
        }
        header('Location: ../onebyone/location');
    }
    
    /**
     * @todo insert image from posted data
     * */
    public function insertImage(){
        global $CONFIG;
        $data = $_POST;
        
        $name = 'filename';
        $path = '';
        
        $uploaded_file = uploadFile($name, $path, 'image');
        
        //if uploaded
        if($uploaded_file['status'] != '0'){
            //validate email and get short_namecode
            $validateEmail = $this->validateEmail($data['email']);
            if($validateEmail['status'] != 'success'){
                $this->msg->add('e', 'Email validation Failed');
                header('Location: ../onebyone/image');
                exit;
            }
            
            $personID = $validateEmail['personID'];
            $username = $validateEmail['short_namecode'];
            $tmp_name = $uploaded_file['full_name'];
            $entry = $uploaded_file['real_name'];
            $image_name_encrypt = md5($entry);
            
            $dataExist = $this->imagezip->dataExist($personID, $entry);
            
            if($dataExist){
                $path_entry = $CONFIG['default']['upload_path'];
                $path_data = 'public_assets/';
                $path_user = $path_data.$username;
                $path_img = $path_user.'/img';
                $path_img_1000px = $path_img.'/1000px';
                $path_img_500px = $path_img.'/500px';
                $path_img_100px = $path_img.'/100px';
                
                $fileinfo = getimagesize($path_entry.'/'.$tmp_name);
                
                $toCreate = array($path_user, $path_img, $path_img_1000px, $path_img_500px, $path_img_100px);
                createFolder($toCreate, 0755);
                
                copy($path_entry."/".$tmp_name, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg');
                if(!@ copy($path_entry."/".$tmp_name, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg')){
                    $status = "error";
                    $msg= error_get_last();
                }
                else{
                    $src_tmp = $path_entry."/".$tmp_name;
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
                    $fileToInsert = array('filename' => $entry,'md5sum' => $image_name_encrypt, 'directory' => '', 'mimetype' => $fileinfo['mime']);
                    
                    $insertImage = $this->imagezip->updateImage($personID, $fileToInsert);
                    $this->msg->add('s', 'Update success');
                } // end if copy
                
            }else{
                $this->msg->add('e', 'Data is not exist');
            }
            
        }else{
            $this->msg->add('e', $uploaded_file['message']);
        }
        unlink($src_tmp);
        header('Location: ../onebyone/image');
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
