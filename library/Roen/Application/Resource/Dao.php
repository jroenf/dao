<?php
/**
 * Application resource: Data Acces Object
 * Kan een xmlrcp-server zijn of een tabledefinition.
 */
class Roen_Application_Resource_Dao
extends Zend_Application_Resource_ResourceAbstract {
    
    protected $_dao;

    public function init() {
        return $this->_getDao();
    }

    protected function _getDao() {
        if (null === $this->_dao) {
            $options = $this->getOptions();
            $dataresource = $options['dataresource'];
            $dataresourceClass =$options['class'];

            // dataresource kan vanuit de bootstrap komen, 
            // bijv een bootstrap.resource->multidb of ->tabledefinition
            $bootstrapOptions = $this->getBootstrap()->getOptions();
            if (isset($bootstrapOptions['resources'][$dataresource])) {
                $dataresource = $this->getBootstrap()
                        ->bootstrap($dataresource)
                        ->getResource($dataresource);
            }
            if (class_exists($dataresourceClass)) {
                $this->_dao = new $dataresourceClass($dataresource);
            }
        }
        return $this->_dao;
    }
}

