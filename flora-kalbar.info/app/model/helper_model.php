<?php
/* contoh models */

class helper_model extends Database {
	
	public function getData_sel($parameter){
			
			if($parameter['status'] == "true"){
				if($parameter['condition'] != ""){
					$bindCur = ",:friend_cv";
				}else{
					$bindCur = ":friend_cv";
				}
			} else {
				$bindCur = "";
			}
            
			$data['sql'] = "begin ".$parameter['package']."(".$parameter['condition']." ".$bindCur."); end;";
			$data['condition'] = $parameter['condition'];
			$data['value'] = $parameter['value'];
			
			$hasil = $this->fecthData($data,true);
			
			return $hasil;
			
        }
}
?>
