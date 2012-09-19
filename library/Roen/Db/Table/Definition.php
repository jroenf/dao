<?php
/**
 * Is niet afgemaakt.
 */
class Roen_Db_Table_Definition extends Zend_Db_Table_Definition {
    /**
     *
     * @param Zend_Db_Adapter_Abstract $db
     * @return Roen_Db_Table_Definition
     */
    public function setDb(Zend_Db_Adapter_Abstract $db) {
        foreach ($this->_tableConfigs as $tableConfig) {

        }
        return $this;
    }
}

