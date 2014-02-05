<?php

/* Kumpulan fungsi umum yang sering digunakan */

function base_url() {
	global $config;
	
	$base_url = $config['base_url'];
	
	return $base_url;
}

function change_date_simple($date_data, $type, $order_by)
{
	/* ex : change_date_to_slash('2012-01-01', 'slash', 'by_date') */
	
	($date_data !='') ? $status = 1 : exit ('Date not complete');
	
	if ($type == 'slash')
	{
		list ($tahun, $bulan, $tanggal) = explode ('-',$date_data);
	
		if ($order_by == 'by_year') $new_date = "$tahun/$bulan/$tanggal";
		if ($order_by == 'by_month') $new_date = "$bulan/$tanggal/$tahun";
		if ($order_by == 'by_date') $new_date = "$tanggal/$bulan/$tahun";
	}
	else if ($type == 'strip')
	{
		list ($tahun, $bulan, $tanggal) = explode ('/',$date_data);
	
		if ($order_by == 'by_year') $new_date = "$tahun-$bulan-$tanggal";
		if ($order_by == 'by_month') $new_date = "$bulan-$tanggal-$tahun";
		if ($order_by == 'by_date') $new_date = "$tanggal-$bulan-$tahun";
	}
	
}
	
function simple_paging($paging_data, $limit)
{
	if ($paging_data==0)
	{
		echo '<script type=text/javascript>alert("Page Not Found"); window.location.href="?pid=1";</script>';
	}
	if ($paging_data== 1)
	{
		$paging = ((($paging_data - 1) * $limit));
	}else
	{
		$paging = ((($paging_data - 1) * $limit) + 1);
	}
	
	return $paging;
}
	
function form_validation($data)
{
	if (!$data) return false;
	$valid_post_vars = $data;
						
	$dataArr = array ();			
	foreach ($valid_post_vars as $key => $value) {
		//echo $key;
		//echo $value;
		//$prefix_post_vars = "p_";
		//$valid_post_var_name = $prefix_post_vars . $i_vpv;
		
		$valid_post_var_value = trim(htmlspecialchars($value));
		
		//$$valid_post_var_name = $valid_post_var_value;

		$dataArr[$key] = $valid_post_var_value;
		
	}
	
	return $dataArr;
	//print_r($dataArr);
}
	
function clear_var($data)
{
	return $$data = '';
}

function under_development() {

	echo 'Maaf, Situs ini sedang dalam perbaikan';
	
	exit;
}

function redirect($data) {
	
	echo "<meta http-equiv=\"Refresh\" content=\"0; url={$data}\">";

}

function uploadFile($data) {
	global $CONFIG;

	if ($_FILES[$data]["error"] > 0)
		{
			echo "Return Code: " . $_FILES[$data]["error"] . "<br>";
		}
	else
		{
			$_FILES[$data]["name"];
			($_FILES[$data]["size"] / $CONFIG['default']['max_filesize']);
			$_FILES[$data]["tmp_name"];

		if (file_exists($CONFIG['default']['upload_path']. $_FILES[$data]["name"]))
		  {
				$failed_result = 0;
				return $failed_result;
		  }
		else
		  {
				move_uploaded_file($_FILES[$data]["tmp_name"],$CONFIG['default']['upload_path'] . $_FILES[$data]["name"]);
				$success_result = 1;
				return $success_result;
		  }
		}
}

function encode($data=false)
{
	$hasil = base64_encode(serialize($data));
	return $hasil;
}

function decode($data=false)
{
	$hasil = unserialize(base64_decode($data));
	return $hasil;
}

function getindexzip($name=null)
{
	
	if ($name==null) return false;
	
	$zip = new ZipArchive;

	if ($zip->open($name) == TRUE) {
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$filename[] = $zip->getNameIndex($i);
		 
		}
	}
	
	if (is_array($filename)) return $filename;
	return false;
}

function unzip($name=null)
{
	global $CONFIG;
	
	if ($name==null) return false;
	
	$zip = new ZipArchive;
	if ($zip->open($name) === TRUE) {
		$zip->extractTo($CONFIG['zip']['path']);
		$zip->close();
		return true;
	} 
	
	return false;
}
?>
