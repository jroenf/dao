<?php
class Roen_Db_Table_FromDefinition extends Zend_Db_Table {
    /**
     * De reden dat ik deze table maak is omdat ik een table 
     * ook optionele 'parent'-waarden wil kunnen laten ophalen voor
     * in een form.
     */
    /**
     * array met tableClasses (namen) van alle tables die in de 
     * definition een reference zijn van deze table
     * @var array
     */
    protected $_referenceTableClasses = array();
    /**
     *
     * @var array
     * alle tables die refereren naar de huidige table
     * keys zijn de refTableClass namen
     * entrys zijn de Roen_Db_Table_FromDefinition objecten
     */
    protected $_referenceTables = array();
    /**
     *
     * @var Zend_Db_Table_Definition
     */
    protected $_definition;
    /**
     *
     * @param array $config
     * @param Zend_Db_Table_Definition $definition 
     */
    public function   __construct($config = array(), $definition = null) {
        if (!($definition instanceof Zend_Db_Table_Definition)) {
            trigger_error(__CLASS__ .'::' . __METHOD__ . 'Hiervoor moet je echt een tabledefinition gebruiken',
                    E_USER_NOTICE
                    );
        }
        parent::__construct($config, $definition);
    }

    public function getReferenceTableValues($tableClass) {
        $reference = $this->getReference($tableClass);
            //assoc-arr met keys: columns (vaule = array), refTableClass(=$tableClass?) en refColumns (value = array)
        $table = $this->getReferenceTable($reference['refTableClass']);
        $select = $table->select()->from($table,array('id','naam'));
        return $table->getAdapter()->fetchPairs($select);
    }

    /**
     *
     * @param <type> $tableClass
     * @return Roen_Db_Table_FromDefinition
     */
    public function getReferenceTable($tableClass) {
        if (!$this->_hasReferenceTable($tableClass)) {
            throw new Zend_Exception('Er is geen table class in '.$this->getDefinitionConfigName().' die '.$tableClass.' heet');
        }
        if (!array_key_exists($tableClass,$this->_referenceTables)) {
            $this->_referenceTables[$tableClass] =
                new Roen_Db_Table_FromDefinition($tableClass, $this->getDefinition());
        }
        return $this->_referenceTables[$tableClass];
    }
    /**
     * Haalt een array op met arrays.
     * Keys zijn de kolommen in huidige table.
     * Values zijn (array) alle mogelijke waarden voor deze kolom.
     * Deze waarden worden opgehaald uit de reference tables.
     * @return <type>
     */
    public function getReferenceValues() {
        $referenceValues = array();
        foreach ($this->_getReferenceMapNormalized() as $referenceArray) {
            $table = new Zend_Db_Table($referenceArray['refTableClass'], Zend_Registry::get('tabledefinition'));
            $select = $table->select()->from($table,array('id','naam'));
            foreach ($referenceArray['columns'] as $referredColumn) {
                $referenceValues[$referredColumn] = $table->getAdapter()->fetchPairs($select);
            }
        }
        return $referenceValues;
    }

    protected function _hasReferenceTable($tableClass) {
        return in_array($tableClass, $this->_getReferenceTableClasses());
    }

    protected function _getReferenceTableClasses() {
        if (empty($this->_referenceTableClasses)) {
            $this->_setReferenceTableClasses();
        }
        return $this->_referenceTableClasses;
    }

    protected function _setReferenceTableClasses() {
        if (!empty($this->_referenceMap)) {
            foreach($this->_referenceMap as $reference) {
                $this->_referenceTableClasses[] = $reference['refTableClass'];
            }
        }
    }
    
    public function  getReferenceMapNormalized() {
        return $this->_getReferenceMapNormalized();
    }

    public function  getReferenceValue($reference) {
        //$this->return $this->_referenceMap;
    }
}
