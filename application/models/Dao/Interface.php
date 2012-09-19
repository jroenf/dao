<?php
/**
 * DAO staat voor Data Access Object
 * Deze geeft altijd een array terug aan
 * de mapper, die de 'ruwe'data
 * vertaalt in een object. 
 */
interface Model_Dao_Interface {
    /**
     * @return array
     */
    public function fetchAll($tableNaam, $options = null);
    /**
     * @return array
     */
    public function find($tableNaam,$id);

    public function findIdNaamArray($tableNaam,$options = null);

    
    
}