<?php

class browseHelper extends Database {
	
    /**
     * @todo retrieve all data from table Taxon
     * 
     * @param $condition = true/false
     * @param $field = field name
     * @param $value = value
     * @return id, rank, morphotype, fam, gen, sp, subtype, ssp, auth, notes
     */
    function dataTaxon($condition,$field,$value){
        if($condition==true){
            $sql = "SELECT * FROM `taxon` WHERE $field='$value'";
        }
        elseif($condition==false){
            $sql = "SELECT * FROM `taxon`";
        }
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all images from taxon data
     * @param $data = id taxon
     */
    function showImgTaxon($data){
        $sql = "SELECT * 
                FROM `det` INNER JOIN `img` ON 
                    det.taxonID='$data' AND det.indivID=img.indivID GROUP BY img.md5sum LIMIT 0,5";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all data from table indiv from selected taxon
     * 
     * @param $data=id taxon
     * @return 
     */
    function dataIndiv($data){
        $sql = "SELECT * 
                FROM `det` INNER JOIN `indiv` ON 
                    det.taxonID='$data' AND det.indivID=indiv.id
                INNER JOIN `person` ON
                    indiv.personID=person.id
                INNER JOIN `locn` ON
                    locn.id=indiv.locnID
                GROUP BY det.indivID";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all images from indiv data
     * @param $data = id indiv
     */
    function showImgIndiv($data){
        $sql = "SELECT * FROM `img` WHERE indivID='$data' LIMIT 0,5";
        $res = $this->fetch($sql,1);
        return $res;
    }
	
}
?>