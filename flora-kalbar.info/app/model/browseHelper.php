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
            $res = $this->fetch($sql,1);
            return $res;
        }
        elseif($condition==false){
            $sql = "SELECT * FROM `taxon`";
            $res = $this->fetch($sql,1);
            
            //PAGINATION
            if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
            } else {
               $pageno = 1;
            } // if
            $rows_per_page = 10;
            $lastpage      = ceil(count($res)/$rows_per_page);
            $pageno = (int)$pageno;
            if ($pageno > $lastpage) {
               $pageno = $lastpage;
            } // if
            if ($pageno < 1) {
               $pageno = 1;
            } // if
            $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
            $sqlLimit = $sql.' '.$limit;
            $resLimit = $this->fetch($sqlLimit,1);
            if($resLimit){
                $return['result'] = $resLimit;
                $return['pageno'] = $pageno;
                $return['lastpage'] = $lastpage;
                return $return;
            }
            else{return false;}
        }
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
    function dataIndiv($action,$field,$value){
        if($action=='indivTaxon'){
            $sql = "SELECT * 
                    FROM `det` INNER JOIN `indiv` ON 
                        det.$field='$value' AND det.indivID=indiv.id
                    INNER JOIN `person` ON
                        indiv.personID=person.id
                    INNER JOIN `locn` ON
                        locn.id=indiv.locnID
                    GROUP BY det.indivID";
        }
        
        if($action=='indivLocn'){
            $sql = "SELECT indiv.id as indivID, indiv.locnID, indiv.plot, indiv.tag, indiv.personID, locn.*, person.*
                    FROM `indiv` INNER JOIN `locn` ON 
                        $value=indiv.locnID
                    INNER JOIN `person` ON
                        indiv.personID=person.id
                    GROUP BY indiv.id";
        }
        
        if($action=='indivPerson'){
            $sql = "SELECT indiv.id as indivID, indiv.locnID, indiv.plot, indiv.tag, indiv.personID, locn.*, person.*
                    FROM `indiv` INNER JOIN `locn` ON 
                        $value=indiv.personID
                    INNER JOIN `person` ON
                        $value=person.id
                    GROUP BY indiv.id";
        }
        
        $res = $this->fetch($sql,1);
        
        //PAGINATION
            if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
            } else {
               $pageno = 1;
            } // if
            $rows_per_page = 10;
            $lastpage      = ceil(count($res)/$rows_per_page);
            $pageno = (int)$pageno;
            if ($pageno > $lastpage) {
               $pageno = $lastpage;
            } // if
            if ($pageno < 1) {
               $pageno = 1;
            } // if
            $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
            $sqlLimit = $sql.' '.$limit;
            $resLimit = $this->fetch($sqlLimit,1);
            if($resLimit){
                $return['result'] = $resLimit;
                $return['pageno'] = $pageno;
                $return['lastpage'] = $lastpage;
                return $return;
            }
            else{return false;}
    }
    
    /**
     * @todo retrieve all data from table Location
     * 
     * @param $condition = true/false
     * @param $field = field name
     * @param $value = value
     * @return id, rank, morphotype, fam, gen, sp, subtype, ssp, auth, notes
     */
    function dataLocation($condition,$field,$value){
        if($condition==true){
            $sql = "SELECT * FROM `locn` WHERE $field='$value'";
            $res = $this->fetch($sql,1);
            return $res;
        }
        elseif($condition==false){
            $sql = "SELECT * FROM `locn`";
            $res = $this->fetch($sql,1);
            
            //PAGINATION
            if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
            } else {
               $pageno = 1;
            } // if
            $rows_per_page = 10;
            $lastpage      = ceil(count($res)/$rows_per_page);
            $pageno = (int)$pageno;
            if ($pageno > $lastpage) {
               $pageno = $lastpage;
            } // if
            if ($pageno < 1) {
               $pageno = 1;
            } // if
            $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
            $sqlLimit = $sql.' '.$limit;
            $resLimit = $this->fetch($sqlLimit,1);
            if($resLimit){
                $return['result'] = $resLimit;
                $return['pageno'] = $pageno;
                $return['lastpage'] = $lastpage;
                return $return;
            }
            else{return false;}
        }
    }
    
    /**
     * @todo retrieve all data from table person
     * 
     * @param $condition = true/false
     * @param $field = field name
     * @param $value = value
     * @return id, rank, morphotype, fam, gen, sp, subtype, ssp, auth, notes
     */
    function dataPerson($condition,$field,$value){
        if($condition==true){
            $sql = "SELECT * FROM `person` WHERE $field='$value'";
            $res = $this->fetch($sql,1);
            return $res;
        }
        elseif($condition==false){
            $sql = "SELECT * FROM `person`";
            $res = $this->fetch($sql,1);
            
            //PAGINATION
            if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
            } else {
               $pageno = 1;
            } // if
            $rows_per_page = 10;
            $lastpage      = ceil(count($res)/$rows_per_page);
            $pageno = (int)$pageno;
            if ($pageno > $lastpage) {
               $pageno = $lastpage;
            } // if
            if ($pageno < 1) {
               $pageno = 1;
            } // if
            $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
            $sqlLimit = $sql.' '.$limit;
            $resLimit = $this->fetch($sqlLimit,1);
            if($resLimit){
                $return['result'] = $resLimit;
                $return['pageno'] = $pageno;
                $return['lastpage'] = $lastpage;
                return $return;
            }
            else{return false;}
        }
    }
    
    /**
     * @todo retrieve all images from indiv data
     * @param $data = id indiv
     */
    function showImgIndiv($data,$limit,$limitVal){
        if($limit==TRUE){
            $sql = "SELECT * FROM `img` WHERE indivID='$data' LIMIT $limitVal";
        }
        elseif($limit==FALSE){
            $sql = "SELECT * FROM `img` WHERE indivID='$data'";
        }
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
                    indiv.id='$data' AND locn.id=indiv.locnID
                INNER JOIN `person` ON
                    person.id=indiv.personID";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo retrieve all det from indiv selected
     * @param $data = id indiv
     */
    function dataDetIndiv($data){
        $sql = "SELECT * 
                FROM `det` INNER JOIN `taxon` ON 
                    indivID='$data' AND taxon.id=det.taxonID
                INNER JOIN `person` ON
                    person.id=det.personID";
        $res = $this->fetch($sql,1);
        return $res;
    }
    
    /**
     * @todo update indiv data selected
     * @param $data = POST indiv
     * @param $id = id indiv
     */
    function updateIndiv($data,$id){
        $sql = "UPDATE `indiv` SET `locnID` = '".$data['locnID']."', `plot` = '".$data['plot']."', `tag` = '".$data['tag']."' WHERE `id` = $id;";
        $res = $this->query($sql,0);
        if($res){return true;}
    }
    
    /**
     * @todo search from table taxon
     * 
     */
    function searchTaxon($data){
        $sql = "SELECT * FROM `taxon` WHERE `fam` LIKE '%$data%' OR `gen` LIKE '%$data%' OR `sp` LIKE '%$data%'";
        $res = $this->fetch($sql,1);
        return $res;
    }
	
}
?>