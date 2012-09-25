<?php
abstract class Model_Pack_Abstract implements Iterator, Countable {
    
    protected $_position = 0;
    
    protected $_count;
    /**
     *
     * @var Model_Mapper_Default
     */
    protected $_mapper;
    protected $_resultSet;
    protected $_modelnaamPrefix = 'Model_';
    protected $_modelnaam;

    protected $_hasFactory = false;

    public function __construct($results,$mapper = null,$modelnaam = null)
    {
        $this->_resultSet = $results;
        $this->_checkModelNaam($modelnaam);
        if ($mapper != null) {
            $this->_mapper = $mapper;
        }
    }

    protected function _checkModelNaam($modelnaam) {
        if ($modelnaam !== null) {
            $this->_modelnaam = $modelnaam;
        }
        if ($this->_modelnaam === null) {
            throw new Zend_Exception('Er is in het Pack geen modelnaam gezet');
        }
    }

    /**
     * 
     * @return int
     */
    public function count()
    {
        if (null === $this->_count) {
            $this->_count = count($this->_resultSet);
        }
        return $this->_count;
    }
    
    /**
     * 
     * @return Model_Abstract
     */
    public function current()
    {
        $key = key($this->_resultSet);
        $result  = $this->_resultSet[$key];
        //$result = current($this->_resultSet);
        if (is_array($result)){// instanceof Model_Pack_Abstract) {
            if ($this->_hasFactory) {
                $factory = $this->_modelnaamPrefix.$this->_modelnaam.'_Factory';
                $createFunc = 'create'.$this->_modelnaam;
                $result = call_user_func(array($factory,$createFunc), $result, $this->_mapper);
            } else {
                $modelClass = $this->_modelnaamPrefix.$this->_modelnaam;
                $result = new $modelClass(
                        $result,
                        $this->_mapper
                    );
            }
            $this->_resultSet[$key] = $result;
        }
        return $result;
    }

    public function key()
    {
        return key($this->_resultSet);
    }

    public function next()
    {
        return next($this->_resultSet);
    }

    public function rewind()
    {
        return reset($this->_resultSet);
    }

    public function valid()
    {
        return !is_null(key($this->_resultSet));
    }
}
