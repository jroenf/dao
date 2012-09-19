<?php
abstract class Model_Mapper_Abstract {
    /**
     *
     * @var Model_Dao_Tabledefinition_Default
     */
    protected $_dataAccessObject;

    public function  __construct($options) {
        if ($options instanceof Model_Dao_Db_Abstract) {
            $this->setDataAccessObject($options);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions($options) {
        if (array_key_exists('dao', $options)) {
            $this->setDataAccessObject($options['dao']);
        }
    }

    /**
     *
     * @return Model_Dao_Tabledefinition_Default
     */
    public function getDataAccessObject() {
        if ($this->_dataAccessObject == null) {
            throw new Zend_Exception('In '.__CLASS__.' met '.__FUNCTION__.' is er geen DataAccessObject gevonden');
        }
        return $this->_dataAccessObject;
    }

    public function getReferenceIdNaamArray($tablename = null) {
        return $this->_dataAccessObject->getReferenceIdNaamArray($tablename);
    }

    public function setDataAccessObject($dataAccessObject) {
        $this->_dataAccessObject = $dataAccessObject;
    }
    /**
     * @param int
     * @return array Een enkerel array met keys de params en values waarden.
     */
    abstract public function find($id);
    /**
     * @return array Een array vol arrays met gegevens
     */
    abstract public function fetchAll();

    public function getArrayIdNaam() {
        return $this->_dataAccessObject->getArrayIdNaam();
    }

    
}
