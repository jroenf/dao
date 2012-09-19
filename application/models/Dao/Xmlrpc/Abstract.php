<?php

abstract class Model_Dao_Xmlrpc_Abstract implements Model_Dao_Interface {
    /**
     *
     * @var Zend_XmlRpc_Client
     */
    static protected $_xmlrpcClient;

    static protected $_clientaddress;

    /**
     *
     * @param Zend_XmlRpc_Client|string $client
     */
    public function  __construct($client = null) {
        //print_r($tabledefinition);exit();
        if (self::$_xmlrpcClient !== null) {
            self::setClient($client);
        }
    }

    public static function setClient($client = null) {
        if ($client == null && self::$_clientaddress != null)
        {
            self::$_xmlrpcClient = new Zend_XmlRpc_Client(self::$_clientaddress);
        } 
        elseif (is_string($client))
        {
            self::$_clientaddress = $client;
            self::$_xmlrpcClient = new Zend_XmlRpc_Client(self::$_clientaddress);
        } 
        elseif ($client instanceof Zend_XmlRpc_Client)
        {
            self::$_xmlrpcClient = $client;
        }
    }


    /**
     * @param string $tablename
     * @param struct | null $options
     * @return array
     */
    public function fetchAll($tablename,$options = null) {
        return
            self::$_xmlrpcClient->call('dao.fetchAll',array($tablename, $options));
    }
    /**
     *
     * @param string $tablename
     * @param int $id
     * @return array
     */
    public function find($tablename,$id) {
        return self::$_xmlrpcClient->call('dao.find',array($tablename, (int)$id));
    }
    
    public function findIdNaamArray($tablename) {
        return self::$_xmlrpcClient->call('dao.findIdNaamArray',$tablename);
    }


}
