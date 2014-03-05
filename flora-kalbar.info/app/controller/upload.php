<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class upload extends Controller {
	
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
		
		$this->collectionHelper = $this->loadModel('collectionHelper');
        $this->excelHelper = $this->loadModel('excelHelper');
	}
	
	public function index(){

		return $this->loadView('upload');

	}
    
    /**
     * @todo upload zip function basedomain/upload/zip
     * 
     * @see s_linux_unzip Function
     * @see unzip Function
     * @see createFolder Function
     * 
     * */
    function zip(){
        global $CONFIG;
        
        $name = 'imagezip';
        $path = '';
        $username = $_POST['username'];
        
        $uploaded_file = uploadFile($name, $path);
        if($uploaded_file['status'] != '0'){
            $path_extract = $uploaded_file['full_path'].$uploaded_file['raw_name'];
            $file = $uploaded_file['full_path'].$uploaded_file['full_name'];
            
            if($CONFIG['default']['unzip'] == 's_linux'){
                s_linux_unzip($file, $path_extract);
            }elseif($CONFIG['default']['unzip'] == 'zipArchive'){
                unzip($file, $path_extract);
            }
            
            $path_data = 'public_assets/';
            $path_user = $path_data.$username;
            $path_img = $path_user.'/img';
            $path_img_ori = $path_img.'/ori';
            $path_img_500px = $path_img.'/500px';
            $path_img_100px = $path_img.'/100px';
            
            $toCreate = array($path_user, $path_img, $path_img_ori, $path_img_500px, $path_img_100px);
            $permissions = 0755;
            createFolder($toCreate, $permissions);
            
            $images = $this->GetContents($path_extract);
            $list = count($images);
            
            foreach ($images as $image){
                $entry = $image['filename'];
                $path_entry = $image['path'];
                
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
                            
                            copy($path_entry."/".$entry, $path_img_ori.'/'.$image_name_encrypt.'.ori.jpg');
                            if(!@ copy($path_entry."/".$entry, $path_img_ori.'/'.$image_name_encrypt.'.ori.jpg')){
                                $status = "error";
                                $msg= error_get_last();
                            }
                            else{
                                $src_tmp = $path_entry."/".$entry;
                                $dest_500px = $CONFIG['default']['root_path'].'/'.$path_img_500px.'/'.$image_name_encrypt.'.500px.jpg';
                                $dest_100px = $CONFIG['default']['root_path'].'/'.$path_img_100px.'/'.$image_name_encrypt.'.100px.jpg';
                                
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
                                
                                //add file info to database here
                                
                                $status = 'success';
                                $msg = 'File uploaded';
                            }
                        }
                    }
                }
            }
        }else{
            $status = "error";
            $msg = $uploaded_file['message'];
        }
        deleteDir($path_extract);
        echo json_encode(array('status' => $status, 'message' => $msg));
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
    
    function validateUsername(){
        $username = $_POST['username'];
        echo json_encode(array('status' => "success"));
        exit;
    }
	
	function parseExcel()
	{
		global $EXCEL;
		
		
		if ($_FILES){
			
			$numberOfSheet = 5;
			$startRowData = 1;
			$startColData = 1;
			$formNametmp = array_keys($_FILES);
			$formName = $formNametmp[0];
			
			if (empty($formName)) die;
			
			$startTime = microtime(true);
			/* parse data excel */
			$parseExcel = $this->excelHelper->fetchExcel($formName, $numberOfSheet,$startRowData,$startColData);
			
			
			if ($parseExcel){
				foreach ($parseExcel as $key => $val){
					
					$field = implode(',',$val['field_name']);
					
					$data = array();
					foreach ($val['data'] as $keys => $value){
						
						foreach ($value as $k => $v){
							$data[$val['field_name'][$k]] = $v;
						}
						
						$newData[$val['sheet']]['data'][] = $data; 
						
					}
					
				}
				
				if ($newData){
					$referenceQuery = $this->excelHelper->referenceData($newData);
					
					$masterQuery = $this->excelHelper->parseMasterData($newData,$subtitute);
					$masterQuery['rawdata']['img'] =  $referenceQuery['rawdata']['img'];
					
					
					
					
					
					$priority = array('taxon','locn','person');
					$masterPriority = array('indiv','img','det','obs','coll');
					
					$param['ref'] = $referenceQuery;
					$param['ref_priority'] = $priority;
					$param['master'] = $masterQuery;
					$param['master_priority'] = $masterPriority;
					
					
					$insertData = $this->collectionHelper->insertCollFromExcel($param);
					
					$endTime = microtime(true);
					
					if ($insertData) echo 'Insert Success on '. execTime($startTime,$endTime);
					else echo 'insert data failed';
				}
			}
		}
	}
	
}

?>
