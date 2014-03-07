<?php
class collectionHelper extends Database {

	/* generate reference query */
	function insertReference($newData=array(),$priority=array())
	{
		if (empty($newData)) return false;
		
		$ignore = array('img');
		$query = false;
		
		$sequence = $this->secqInsert($newData['query'],$priority, $ignore);
		// pr($sequence);
		if ($sequence){
			
			$query = $this->runQuery($sequence);
			logFile('query references success =>'.serialize($sequence));
		}
		return $query;
	}
	
	/* generate master query */
	function insertMaster($newData=array(),$priority=array())
	{
		if (empty($newData)) return false;
		
		$ignore = array('img');
		$query = false;
		
		$param['indiv']['convertkey'] = array('locnID');
		$param['indiv']['table'] = array('locn');
		$param['indiv']['field'] = array('id');
		$param['indiv']['condition'] = array('short_namecode');
		
		$param['det']['convertkey'] = array('personID','taxonID');
		$param['det']['table'] = array('person','taxon');
		$param['det']['field'] =  array('id','id');
		$param['det']['condition'] =  array('short_namecode','short_namecode');
		
		$param['obs']['convertkey'] = array('personID');
		$param['obs']['table'] = array('person');
		$param['obs']['field'] =  array('id');
		$param['obs']['condition'] =  array('short_namecode');
		
		$param['coll']['convertkey'] = array('personID');
		$param['coll']['table'] = array('person');
		$param['coll']['field'] =  array('id');
		$param['coll']['condition'] =  array('short_namecode');
		
		$param['img']['convertkey'] = array('personID');
		$param['img']['table'] = array('person');
		$param['img']['field'] =  array('id');
		$param['img']['condition'] =  array('short_namecode');
		
		$data['indiv'] = $this->parseRef($newData['rawdata'], 'indiv', $param['indiv']);
		$data['det'] = $this->parseRef($newData['rawdata'], 'det', $param['det']);
		$data['obs'] = $this->parseRef($newData['rawdata'], 'obs', $param['obs']);
		$data['coll'] = $this->parseRef($newData['rawdata'], 'coll', $param['coll']);
		$data['img'] = $this->parseRef($newData['rawdata'], 'img', $param['img']);
		
		$sequence = $this->secqInsert($data,$priority, $ignore);
		// pr($sequence);
		
		if ($sequence){
			$query = $this->runQuery($sequence);
			logFile('query master success =>'.serialize($sequence));
		}
		return $query;
	}
	
	/* insert data from excel */
	function insertCollFromExcel($newData=array())
	{
		
		$referenceQuery = $newData['ref'];
		$masterQuery = $newData['master'];
		$priority = $newData['ref_priority'];
		$masterPriority = $newData['master_priority'];
		
		$insertRefData = false;
		$insertMasterData = false;
		
		$startTransaction = $this->begin();
		if (!$startTransaction) return false;
		logFile('====TRANSACTION READY====');
		
		$insertRefData = $this->insertReference($referenceQuery,$priority);
		$insertMasterData = $this->insertMaster($masterQuery,$masterPriority);
		
		if ($insertRefData or $insertMasterData){
			$this->rollback();
			logFile('====ROLLBACK TRANSACTION====');
			return false;
		}else{
			$this->commit();
			logFile('====COMMIT TRANSACTION====');
			return true;
		}

		exit;
		
	}
	
	/* run sql query */
	function runQuery($data,$last=false)
	{
		
		if ($data){
			
			try {
			
				
				$failed = false;
				$count = 0;
				foreach ($data as $val){
					logFile('excecute query =>'.serialize($val));
					$sql = $this->query($val);
					
					if (!$sql) $failed = true;
					$count++;
					if ($count==100){
						usleep(500);
						$count = 0;
					}
					
					
				}
				
			} catch (Exception $e) {
				
			}
				
		}
		
		
		
		
		return $failed;
	}
	
	/* reverse data array */
	function secqInsert($newData=array(), $priority=array(),$ignore=array()){
		
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
	
	/* parse reference field, before searching */
	function parseRef($newData=array(), $index=false,$param=array())
	{
		if (!$index) return false;
		
		$data = $newData[$index];
		
		foreach ($data['data'] as $key => $val){
			
			foreach ($param['convertkey'] as $i =>$o){
				if (array_key_exists($o, $val)){
					$data['data'][$key][$param['convertkey'][$i]] = $this->checkDataRef($param['table'][$i],$param['field'][$i],$param['condition'][$i],$val[$param['convertkey'][$i]]);
				}
				
				
			}
			
		}
		
		if ($data){
			
			
			$sql = array();
			foreach ($data['data'] as $key => $val){
				
				$field = array();
				$datas = array();
				foreach ($val as $k => $v){
					$field[] = $k;
					$datas[] = "'$v'";
				}
				
				$imp = implode(',',$field);
				$imps = implode(',',$datas);
				$sql[] = "INSERT INTO {$index} ({$imp}) VALUES ({$imps})"; 
				
			}
			
			
		}
		return $sql;
	}
	
	/* look for references id */
	function checkDataRef($table=false,$field=false,$cond="short_namecode", $id=false)
	{
		
		if (!$id && !$table && !$field) return false
		
		$sql = "SELECT {$field} FROM {$table} WHERE {$cond} = '{$id}' LIMIT 1";
		// pr($sql);
		$res = $this->fetch($sql);
		if ($res) return $res['id'];
		return false;
	}
	
	/* end excel helper */
	
	
	function insertData($table=false, $data=array())
	{
		if (!$table and empty($data)) return false;
		
		$data = array();
		$data['status'] = false;
		
		foreach ($data as $key=>$val){
			$tmpfield[] = $key;
			$tmpvalue[] = "'{$val}'";
		}
		
		$field = implode (',',$tmpfield);
		$value = implode (',',$tmpvalue);
		
		$sql = "INSERT INTO {$table} ({$field}) VALUES ({$value})";
		$res = $this->query($sql);
		if ($res){
			
			$data['lastid'] = $this->insert_id();
			$data['status'] = true;
		}
		return $data;
	}	
}

?>