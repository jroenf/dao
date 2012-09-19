<?php
abstract class Model_Pack_Abstract implements Iterator, Countable {
        /*
     * bij foreach (iterator):
     *
     * rewind
     * valid
     * current
     * key
     * execute foreach loop
     *
     * next
     * valid
     * current
     * key
     * execute foreach loop
     *
     * next
     * valid stopt als false
     *
     */
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

    public function count()
    {
        if (null === $this->_count) {
            $this->_count = count($this->_resultSet);
        }
        //print_r(__METHOD__.' result: '.$this->_count.'  ');
        return $this->_count;
    }

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
        //return $this->_position;
        //Matthew:
        return key($this->_resultSet);
    }

    public function next()
    {
        return next($this->_resultSet);
        //return $this->resultSet[$this->_position];
        //Matthew: return next($this->_resultSet);
    }

    public function rewind()
    {
        //$this->_position = 0;
        //Matthew:
        return reset($this->_resultSet);
    }

    public function valid()
    {
        //return array_key_exists($this->_resultSet, $this->_position);
        // of zonder position:
        return !is_null(key($this->_resultSet));
    }
}
