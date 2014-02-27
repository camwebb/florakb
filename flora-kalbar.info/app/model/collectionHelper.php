<?php
class collectionHelper extends Database {


	function insertCollFromExcel($newData=array())
	{
		
		if (empty($newData)) return false;
		
		$priority = array('taxon','locn','person');
		
		$sequence = $this->secqInsert($newData,$priority);
		pr($sequence);exit;
		if ($sequence){
			$count = 0;
			foreach ($sequence as $val){
				
				// run insert query here 
				
				$count++;
				if ($count==100){
					usleep(500);
					$count = 0;
				}
			}
		}
	}
	
	function secqInsert($newData=array(), $priority=array()){
		
		if (empty($newData)) return false;
		
		$seq = array();
		
		// looping priority
		foreach ($priority as $val){
			
			// get record from array $newData if priority value match
			foreach ($newData[$val] as $value){
				$seq[] = $value;
			}
		}
		
		return $seq;
	}
}

?>