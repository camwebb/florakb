<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

class home extends Controller {
	
	var $models = FALSE;
	var $view;
<<<<<<< HEAD
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
=======
	public function __construct()
	{
		global $basedomain;
		$this->loadmodule();
        $this->view = $this->setSmarty();
        $this->view->assign('basedomain',$basedomain);
>>>>>>> b2fa80be39aa405a008d64497791f3523b943b95
	}
	
	function loadmodule()
	{
		
		// $this->models = $this->loadModel('frontend');
	}
	
	function index(){
        
		$var = array(1,2,3);
		
		
<<<<<<< HEAD
		// vd($this);
=======
		//vd($this);
>>>>>>> b2fa80be39aa405a008d64497791f3523b943b95
		$this->view->assign('test',$var);
		
		$var = 'masuk';
		return $this->loadView('home');

	}
	
	
	function fetchExcel($sheet=1,$startRow=1,$startCol=0)
	{
		global $EXCEL;
		
			$data = array();
			$newData = array();
			
			$numberOfSheet = $sheet;
			$startRowData = $startRow;
			$startColData = $startCol;
			
			// parameternya adalah name dari input type file
			$excel = $this->excel('tes');
			
			if ($excel){
			
				for ($i=0; $i<$numberOfSheet; $i++){
					
					$data[$i]['sheet'] = $i;
					
					// get field name in current sheet
					$countColl = $excel->colcount($sheet_index=$i);
					$countRow = $excel->rowcount($sheet_index=$i);
					if ($countColl>0){
						for ($a=$startRowData; $a<=$countColl; $a++){
							$data[$i]['field_name'][] = $excel->val($startRowData, ($a), $i);
						}
					}
					
					if ($countRow>0){
						// looping baris
						for ($a=$startRowData; $a<=$countRow; $a++){
							
							// looping kolom
							
							for ($b=$startRowData; $b<=$countColl; $b++){
								
								$data[$i]['data'][$a][] = $excel->val($a+1, ($b), $i);
								
							}
							
						}
					}
					
				}
			}
			
			
			// clean data, if empty pass
			if ($data){
				foreach ($data as $key=>$val){
					
					
					foreach ($val['data'] as $keys=>$values){
						
						$newData[$key]['sheet'] = $val['sheet'];
						$newData[$key]['field_name'] = $val['field_name'];
						
						if (!empty($values[0])){
							
							$newData[$key]['data'][$keys] = $values;
						}
					}
				
				}
			}
			
			return $newData;
			
		
	}
	
	function parseExcel()
	{
		global $EXCEL;
		if ($_FILES){
			
			$numberOfSheet = 5;
			$startRowData = 1;
			$startColData = 1;
			
			$parseExcel = $this->fetchExcel($numberOfSheet,$startRowData,$startColData);
			
			pr($parseExcel);
		}
	}
}

?>
