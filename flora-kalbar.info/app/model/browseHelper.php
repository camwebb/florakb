<?php

class browseHelper extends Database {
	
    /**
     * @todo retrieve all data from table Taxon
     * @return id, rank, morphotype, fam, gen, sp, subtype, ssp, auth, notes
     */
    function dataTaxon(){
        $sql = "SELECT * FROM `taxon`";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all images from taxon data
     * @param $data = id taxon
     */
    function showImg($data){
        $sql = "SELECT * 
                FROM det INNER JOIN img ON 
                    det.taxonID='$data' AND det.indivID=img.indivID";
        $res = $this->fetch($sql,1);
        return $res;
    }
	
}
?>