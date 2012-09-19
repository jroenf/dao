<?php
/**
     * Ik gebruik altijd multidb, dan hoeft ophalen uit db niet.
     * Deze resource werkt ook net als multidb, d.w.z. meerdere
     * tabledefinitions mogelijk.
     * Zet de tabledefinition in /configs/tabledefinition/
     *
     * Config:
     *  resources.tabledefinition.
     *      'tabledefname'.db = 'dbname'
     * Het is natuurlijk handig de tabledefname en dbname hetzelfde te maken.
     * @var array
     */
class Roen_Application_Resource_Tabledefinitions extends Zend_Application_Resource_ResourceAbstract {

    protected $_tabledefinitions = array();

    public function init()
    {
        $multidbResource = $this->getBootstrap()->bootstrap('multidb')->getResource('multidb');
        $options = $this->getOptions();

        foreach ($options as $tabledefinitionName => $params) {
            $dbnaam = $params['db'];
            $db = $multidbResource->getDb($dbnaam);
            
            $tableDefinitionArray = include APPLICATION_PATH.'/configs/tabledefinition/'.$tabledefinitionName.'.php';
            foreach ($tableDefinitionArray as &$singleTableArray) {
                $singleTableArray['db'] = $db;
            }
            //unset($singleTableArray);
            $this->_tabledefinitions[$tabledefinitionName] = new Zend_Db_Table_Definition($tableDefinitionArray);
        }
        return $this;
    }

    public function getTabledefinition($tabledefinition)
    {
        if (isset($this->_tabledefinitions[$tabledefinition])) {
            return $this->_tabledefinitions[$tabledefinition];
        }

        throw new Zend_Application_Resource_Exception(
            'A tabledefinition was tried to retrieve, but was not configured'
        );
    }
}