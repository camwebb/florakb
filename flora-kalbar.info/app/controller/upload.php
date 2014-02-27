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
        
		$var = array(1,2,3);

		$this->view->assign('test',$var);

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
            $path_user = $path_data.$username.'/';
            $path_img = $path_user.'/img';
            $path_img_ori = $path_img.'/ori';
            $path_img_500px = $path_img.'/500px';
            $path_img_100px = $path_img.'/100px';
            
            $toCreate = array($path_user, $path_img, $path_img_ori, $path_img_500px, $path_img_100px);
            $permissions = 0755;
            createFolder($toCreate, $permissions);
            
            echo json_encode(array('status' => 'error', 'message' => 'belom selesai'));
        }else{
            echo json_encode(array('status' => 'error', 'message' => $uploaded_file['message']));
        }
        exit;
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
			
			$parseExcel = $this->excelHelper->fetchExcel($formName, $numberOfSheet,$startRowData,$startColData);
			
			
			// pr($parseExcel);
			// exit;
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
					$generateQuery = $this->excelHelper->generateQuery($newData);
					
					pr($generateQuery);
					$insertData = $this->collectionHelper->insertCollFromExcel($generateQuery);
				}
				
			}
		}
	}
	
	
}

?>
