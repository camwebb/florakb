<?php
defined ('CODEKIR') or exit ( 'Forbidden Access' );

class zip extends Controller {
	
	var $models = FALSE;
	var $view;
	public function __construct()
	{
        global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
	}
	public function loadmodule()
	{
		
		$this->imagezip = $this->loadModel('imagezip');
	}
	
	public function index(){

		return $this->loadView('zip');

	}
    
    /**
     * @todo extract zip function basedomain/zip/extract
     * 
     * @see s_linux_unzip Function
     * @see unzip Function
     * @see createFolder Function
     * @see getContents Function
     * @see resize & crop Function
     * @see validateUsername Function
     * @see imagezip class
     * 
     * */
    function extract($status=NULL,$msg=NULL,$data=NULL){
        global $CONFIG;
        
        $name = $_POST['imagezip'];
        $path = '';
        $path_file = $CONFIG['default']['upload_path'];
        $email = $_POST['email'];
        
        /*$username = $_POST['username'];
        
        $validateUsername = $this->validateUsername($username);

        if($validateUsername['status'] != 'success'){
            $status = "error";
            $msg = "Error occured while validating username";
            
            echo json_encode(array('status' => $status, 'message' => $msg));
            exit;
        }
        
        $personID = $validateUsername['personID'];*/
        
        // input with email        
        $validateEmail = $this->validateEmail($email);
        if($validateEmail['status'] != 'success'){
            $status = "error";
            $msg = "Error occured while validating email";
            
            echo json_encode(array('status' => $status, 'message' => $msg));
            exit;
        }
        $personID = $validateEmail['personID'];
        $username = $validateEmail['short_namecode'];
        //end input with email
        
        if(!empty($name)){
            
            if(preg_match('#\.(zip|ZIP)$#i', $name)){
                
                $tmp_path = md5($name);
                $path_extract = $path_file.'imgprocess/'.$tmp_path;
                $file = $path_file.$name;
                
                //check file zip exist
                if(!file_exists($file)){
                    $status = "error";
                    $msg = "The system cannot find the file specified";
                    
                    echo json_encode(array('status' => $status, 'message' => $msg));
                    exit;
                }
                
                if($CONFIG['default']['unzip'] == 's_linux_unzip'){
                    s_linux_unzip($file, $path_extract);
                }elseif($CONFIG['default']['unzip'] == 'zipArchive'){
                    unzip($file, $path_extract);
                }
                
                $path_data = 'public_assets/';
                $path_user = $path_data.$username;
                $path_img = $path_user.'/img';
                $path_img_1000px = $path_img.'/1000px';
                $path_img_500px = $path_img.'/500px';
                $path_img_100px = $path_img.'/100px';
                
                $toCreate = array($path_user, $path_img, $path_img_1000px, $path_img_500px, $path_img_100px);
                $permissions = 0755;
                createFolder($toCreate, $permissions);
                
                $images = $this->GetContents($path_extract);
                $list = count($images);
                
                $dataNotExist = array();
                
                foreach ($images as $image){
                    $entry = $image['filename'];
                    $path_entry = $image['path'];
                    
                    $len = strlen($path_extract);
                    $folder = substr($path_entry,$len);
                    
                    if(preg_match('#\.(jpg|jpeg|JPG|JPEG)$#i', $entry)){
                        $image_name_encrypt = md5($entry);
                        $fileinfo = getimagesize($path_entry.'/'.$entry);
                        if(!$fileinfo) {
                            $status = "error";
                            $msg = "No file type info";
                        }else{
                            $valid_types = array(IMAGETYPE_JPEG);
                            $valid_mime = array('image/jpeg');
                        
                            if(in_array($fileinfo[2],  $valid_types) || in_array($fileinfo['mime'],  $valid_mime)) {
                                $mime = true;
                            }else{
                                $mime = false;
                            } 
                            
                            if($mime){
                                
                                //check file exist here
                                
                                copy($path_entry."/".$entry, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg');
                                if(!@ copy($path_entry."/".$entry, $path_img_1000px.'/'.$image_name_encrypt.'.1000px.jpg')){
                                    $status = "error";
                                    $msg= error_get_last();
                                }
                                else{
                                    $src_tmp = $path_entry."/".$entry;
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
                                    $fileToInsert = array('filename' => $entry,'md5sum' => $image_name_encrypt, 'directory' => $folder, 'mimetype' => $fileinfo['mime']);
                                    
                                    //check data exist in db
                                    $dataExist = $this->imagezip->dataExist($personID, $entry);
                                    
                                    //if data exist, update data
                                    if($dataExist){
                                        $insertImage = $this->imagezip->updateImage($personID, $fileToInsert);
                                    }else{
                                        //add data information to array
                                        array_push($dataNotExist,$fileToInsert);
                                    }                         
                                } // end if copy
                            }
                        }
                    }
                }
                
                //send dataNotExist information to user   

                $status = 'success';
                $msg = 'File extracted';
                $data['dataNotExist'] = $dataNotExist;
                
                deleteDir($path_extract);
            }else{
                $status = "error";
                $msg = 'Filename must be a zip file';
            }
        }else{
            $status = "error";
            $msg = 'Filename can not be empty';
        }
        
        echo json_encode(array('status' => $status, 'message' => $msg, 'data' => $data));
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
    
    /**
     * @todo get all files in a folder and it's subfolder
     * 
     * @param string $dir = path to directory
     * @var array $files = array to store file information
     * 
     * @return array([0] => array('path' => 'string path to file', 'filename' => 'filename'))
     * 
     * */
    function GetContents($dir,$files=array()) { 
        if(!($res=opendir($dir))) exit("$dir doesn't exist!"); 
            while(($file=readdir($res))==TRUE) 
            if($file!="." && $file!="..")
                if(is_dir("$dir/$file")){
                    $files=$this->GetContents("$dir/$file",$files); 
                }else{
                    $file_info = array('path' => $dir, 'filename' => $file);
                    array_push($files,$file_info); 
                }
        
        closedir($res); 
        return $files; 
    }
    
    /**
     * @todo get id of a user
     * 
     * @param username = short name code from user input
     * @return status = a status of success/error validate
     * @return personID = id for person (if success)
     * 
     * */
    function validateUsername($username){
        $validateUser = $this->imagezip->validateUser($username);
        if($validateUser['id'] != ''){
            $return = array('status' => "success", 'personID' => $validateUser['id']);
        }else{
            $return = array('status' => "error", 'personID' => $validateUser['id']);
        }  
        return $return;
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
    
    function test(){
        $id = '34';
        $filename = 'IMG_0202.jpg';
        $dataExist = $this->imagezip->dataExist($id, $filename);
        pr($dataExist); exit;
    }
	
}

?>
