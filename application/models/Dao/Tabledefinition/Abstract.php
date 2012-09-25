<?php
/**
 * Hierin staat alleen de tabledefinition, niet de table naam,
 * want sommige objecten hebben meer dan 1 table nodig.
 */
abstract class Model_Dao_Tabledefinition_Abstract implements Model_Dao_Interface {
    /**
     *
     * @var Roen_Application_Resource_Tabledefinitions
     */
    static protected $_tabledefinitions;
    /**
     *
     * @var array keys zijn de table namen, waarden zijn de tables
     */
    static protected $_tables = array();
    /**
     *
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    static protected $_db;

    /**
     *
     * @param Zend_Db_Table_Definition $settings 
     */
    public function  __construct($tabledefinitions = null) {
        //print_r($tabledefinition);exit();
        self::setTableDefinitions($tabledefinitions);
    }

    public static function setTableDefinitions($tabledefinitions = null) {
        if ($tabledefinitions == null) {
            if (Zend_Registry::isRegistered('tabledefinitions')) {
                $tabledefinitions = Zend_Registry::get('tabledefinitions');
            } else {
                throw new Zend_Exception('Er is geen tabledefinition gevonden.');
            }
        }
        self::$_tabledefinitions = $tabledefinitions;
    }


    /**
     *
     * @param string $dbnaam
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    public function getDb($dbnaam = null) {
        if ($dbnaam == null) {
            $dbnaam = $this->_defaultdbname;
        }
        return $this->getTable('Eig_Domein',$dbnaam)->getAdapter();
    }
    
    /**
     * Set the db
     * 
     * @param Zend_Db_Adapter_Pdo_Mysql $db
     */
    public function setDb($db) {
        if ($db instanceof Zend_Db_Adapter_Pdo_Mysql) {
            self::$_db = $db;
        }
    }
    /**
     * Fetch all records from one table.
     * Some options for simple selections too.
     * 
     * @param string $tablename
     * @param struct | null $options
     * @return array
     */
    public function fetchAll($tablename,$options = null) {
        $table = $this->getTable($tablename);
        $select = $table->select();
        if ($options != null) {
            if (array_key_exists('where', $options)) {
                foreach ($options['where'] as $field => $waarde) {
                    $comperator = ' = ';
                    $value = $waarde;
                    if (is_array($waarde)) {
                        foreach ($waarde as $naam => $setting) {
                            switch ($naam) {
                                case 'comperator':
                                    $comperator = $setting;
                                    break;
                                case 'expr':
                                    $value = new Zend_Db_Expr($setting);
                                    break;
                                case 'value':
                                    $value = $setting;
                                    break;
                                default:
                                    throw new Zend_Exception('In de dao is fetchAll misgelopen');
                                    break;
                            }
                        }
                    }
                    $select->where($field.$comperator.'?',$value);
                }
            }
            if (array_key_exists('order', $options)) {
                if (is_string($options)) {
                    $options = array($options);
                }
                foreach ($options['order'] as $order) {
                    $select->order($order);
                }
            }
        }
        if ($tablename === 'Eig_Element'){
        }
        return $table->fetchAll($select)->toArray();
    }

    /**
     *
     * @param string $tablename
     * @param int $id
     * @param string | null
     * @return array
     */
    public function find($tablename,$id,$dbnaam = null) {
        $id = (int)$id;
        $table = $this->getTable($tablename,$dbnaam);
        $tableRows = $table->find($id);
        if (count($tableRows) == 1) {
            return $tableRows->current()->toArray();
        } else {
            throw new Zend_Exception('In \''.__CLASS__.'\' heb je met \''.__FUNCTION__.'\' geen success');
        }
    }
    

    /**
     *
     * @param string $tablenaam
     * @return Zend_Db_Table_Abstract
     */
    public function getTable($tablenaam, $dbnaam = null) {
        if ($dbnaam == null) {
            $dbnaam = $this->_defaultdbname;
        }
        if (!isset(self::$_tables[$dbnaam])) {
            self::$_tables[$dbnaam] = array();
        }
        if (!array_key_exists($tablenaam, self::$_tables[$dbnaam])) {
            $this->_setTable($tablenaam, $dbnaam);
        }
        return self::$_tables[$dbnaam][$tablenaam];
    }

    protected function _setTable($tablenaam,$dbnaam = null) {
        if ($dbnaam == null) {
            $dbnaam = $this->_defaultdbname;
        }
        //print_r(self::$_tabledefinitions);exit();
        $table = new Zend_Db_Table($tablenaam,self::$_tabledefinitions->getTabledefinition($dbnaam));
        self::$_tables[$dbnaam][$tablenaam] = $table;
    }
    /**
     *
     * @return Zend_Db_Table_Definition
     */
    public function getTableDefinition($dbnaam = null) {
        if ($dbnaam == null) {
            $dbnaam = $this->_defaultdbname;
        }
        return self::$_tabledefinitions->getTabledefinition($dbnaam);
    }
}
