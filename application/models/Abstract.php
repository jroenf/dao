<?php
abstract class Model_Abstract {

    /**
     *
     * @var Model_Mapper_Default
     */
    protected $_defaultMapper;

    /**
     * @var Zend_View_Interface
     */
    protected $_view;
    

    /**
     *
     * @var Model_Mapper_Abstract
     */
    protected $_mapper;
    
    public function __construct($values = null,$mapper = null) {
        if ($values !== null) {
            $this->setValues($values);
        }
        if ($mapper !== null) {
            $this->setMapper($mapper);
        }
    }

    public function __get($name) {
        $funcNames = array();
        $funcNames[] = 'get'.$name;
        $funcNames[] = 'get'.ucfirst($name);
        foreach ($funcNames as $funcName) {
            if (method_exists($this,$funcName)) {
                return $this->$funcName();
            }
        }
    }


    public function  __set($name, $value) {
        /*$funcName = 'set'.$name;
        if (method_exists($funcName) || method_exists('set'.ucfirst($name))) {
            return $this->$function_name($value);
        }*/
    }
    /**
     *
     * @param array $values
     * @return Model_Abstract
     */
    public function setValues(array $values) {
        if (is_array($values)) {
            /*$functionNamer = new Zend_Filter_Word_UnderscoreToCamelCase();
            foreach ($values as $key => $value) {
                $functionName = 'set'.ucfirst($functionNamer->filter($key));
                if (method_exists(__CLASS__, $functionName)) {
                    $this->{$function}($value);
                }
            }*/
            foreach ($values as $key => $value) {
                $naam = '_'.$key;
                $this->$naam = $value;
            }
        }
        return $this;
    }

    public function setMapper($mapper) {
        if ($mapper instanceof Model_Mapper_Abstract) {
            $this->_mapper = $mapper;
        }
    }
    /**
     *
     * @return Model_Mapper_Abstract
     */
    public function getMapper() {
        if ($this->_mapper == null) {
            throw new Zend_Exception('Er is geen mapper gezet in dit model');
        }
        return $this->_mapper;
    }

    /**
     *
     * @return Model_Mapper_Default
     */
    public function getDefaultMapper() {
        if ($this->_defaultMapper == null) {
            if ($this->_mapper instanceof Model_Mapper_Default ) {
                $this->_defaultMapper = $this->_mapper;
            } else {
                $tabledefinition = $this->_mapper->getDataAccessObject()->getTableDefinition();
                $dao = new Model_Dao_Db_Default($tabledefinition);
                $this->_defaultMapper = new Model_Mapper_Default($dao);
            }
        }
        return $this->_defaultMapper;
    }

    // Rendering

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Form
     */
    public function setView(Zend_View_Interface $view = null)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Retrieve view object
     *
     * If none registered, attempts to pull from ViewRenderer.
     *
     * @return Zend_View_Interface|null
     */
    public function getView()
    {
        if (null === $this->_view) {
            require_once 'Zend/Controller/Action/HelperBroker.php';
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView($viewRenderer->view);
        }

        return $this->_view;
    }

}