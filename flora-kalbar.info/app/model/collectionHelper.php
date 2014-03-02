<?php
class collectionHelper extends Database {


	function insertCollFromExcel($newData=array())
	{
		
		// echo phpinfo();exit;
		if (empty($newData)) return false;
		
		$priority = array('taxon','locn','person');
		
		$sequence = $this->secqInsert($newData,$priority);
		// pr($sequence);
		if ($sequence){
			$count = 0;
			
			// run insert query here 
			try {
				// First of all, let's begin a transaction
				$auto = $this->autocommit();
				$start = $this->begin();
				
				if (!$start) return false;
				$failed = false;
				foreach ($sequence as $val){
					$sql = $this->query($val);
					
					if (!$sql) $failed = true;
					$count++;
					if ($count==100){
						usleep(500);
						$count = 0;
					}
					
					
				}
				
				if ($failed) $this->rollback();
				else $this->commit();
			} catch (Exception $e) {
				
				$this->rollback();
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