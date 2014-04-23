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
	
}
?>