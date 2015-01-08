<?php

class browseHelper extends Database {
	
    /**
     * @todo retrieve all data from table Taxon
     * @return id, rank, morphotype, fam, gen, sp, subtype, ssp, auth, notes
     */
    function dataTaxon(){
        $sql= "SELECT * FROM taxon WHERE id in (SELECT det.taxonID FROM det INNER JOIN indiv on indiv.id = det.indivID WHERE indiv.n_status = 0)";
        $res = $this->fetch($sql,1);
        $return['result'] = $res;
        return $return;
    }

    /**
     * @todo retrieve all images from taxon data
     * @param $data = id taxon
     */
    function getImgTaxon($data){
        $sql = "SELECT * 
                FROM `det` INNER JOIN `img` ON 
                    det.taxonID='$data' AND det.indivID=img.indivID GROUP BY img.md5sum LIMIT 0,5";
        $res = $this->fetch($sql,1);
        return $res;
    }

    /**
     * @todo retrieve title from selected species
     * @param $data = id title
     */
    function getTitle($data){
        $sql = "SELECT sp FROM taxon WHERE id = $data";
        $res = $this->fetch($sql,1);
        return $res;
    }
	
    /**
     * @todo retrieve all data from table indiv from selected taxon
     * 
     * @param $action=action selected taxon/locn/person
     * @param $field=field name in db
     * @param $value=id taxon
     * @return 
     */
    function dataIndivTaxon($value){
        $sql = "SELECT * 
                FROM `det` INNER JOIN `indiv` ON 
                    det.taxonID='$value' AND det.indivID=indiv.id AND indiv.n_status='0'
                INNER JOIN `person` ON
                    indiv.personID=person.id
                INNER JOIN `locn` ON
                    locn.id=indiv.locnID
                GROUP BY det.indivID";
        
        $res = $this->fetch($sql,1);
        $return['result'] = $res;
        return $return;
    }

    /**
     * @todo retrieve images from indiv data
     * @param $data = id indiv
     */
    function showImgIndiv($data){
        $sql = "SELECT * FROM `img` WHERE indivID='$data' AND md5sum IS NOT NULL LIMIT 0,5";
        $res = $this->fetch($sql,1);
        return $res;
    }

    /**
     * @todo retrieve all indiv detail
     * @param $data = id indiv
     */
    function detailIndiv($data){
        $sql = "SELECT * 
                FROM `indiv` INNER JOIN `locn` ON 
                    indiv.id='$data' AND locn.id=indiv.locnID AND indiv.n_status='0'
                INNER JOIN `person` ON
                    person.id=indiv.personID";
        $res = $this->fetch($sql,1);
        return $res;
    }

    /**
     * @todo retrieve all images from indiv data
     * @param $data = id indiv
     */
    function showAllImgIndiv($data){
        $sql = "SELECT * FROM `img` WHERE indivID='$data' AND md5sum IS NOT NULL";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all det from indiv selected
     * @param $data = id indiv
     */
    function dataDetIndiv($data){
        $sql = "SELECT det.id as detID, det.*, taxon.*,person.* 
                FROM `det` INNER JOIN `taxon` ON 
                    indivID='$data' AND taxon.id=det.taxonID AND det.n_status='0'
                INNER JOIN `person` ON
                    person.id=det.personID";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all obs from indiv selected
     * @param $data = id indiv
     */
    function dataObsIndiv($data){
        $sql = "SELECT obs.id as obsID, obs.*, person.* 
                FROM `obs` INNER JOIN `person` ON 
                    indivID='$data' AND person.id=obs.personID AND obs.n_status='0'";
        $res = $this->fetch($sql,1);
        return $res;
    }
}
?>