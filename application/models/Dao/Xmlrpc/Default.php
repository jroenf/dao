<?php
class Model_Dao_Xmlrpc_Default extends Model_Dao_Xmlrpc_Abstract {

    static protected $_clientaddress = 'http://www.domainname.nl/xmlrpc';
    /**
     *
     * @param string $tablename
     * @param struct $options
     * @return struct
     */
    public function search($tablename,$options) {

        //try {
            return self::$_xmlrpcClient->call('dao.search', array($tablename, $options));
        //} catch (Zend_XmlRpc_Exception $e) {
        //    echo $e->getCode().': '.$e->getMessage()."\n";
        //    print_r(self::$_xmlrpcClient->getLastRequest());
        //    print_r(self::$_xmlrpcClient->getLastResponse());
        //    print_r($tablename);
        //    print_r($options);
        //    exit();
        //} catch (Zend_XmlRpc_Fault $e) {
        //    echo $e->getCode().': '.$e->getMessage()."\n";
        //    print_r(self::$_xmlrpcClient->getLastRequest());
        //    print_r(self::$_xmlrpcClient->getLastResponse());
        //    print_r($tablename);
        //    print_r($options);
        //    exit();
        //}
        // Voor debuggen is dit ook een goeie:
        //return self::$_xmlrpcClient->getLastResponse();
    }

    public function getEigDomeinReferenceTypeEigDomein($domeinId) {
        return self::$_xmlrpcClient->call('dao.getEigDomeinReferenceTypeEigDomein', $domeinId);
    }
    
    /**
     *
     * @param integer $domeinId
     * @return array als er niets gevonden is is dit een lege array 
     */
    public function getEigDomeinDependentEigPaginas($domeinId) {
        return self::$_xmlrpcClient->call('dao.getEigDomeinDependentEigPaginas', $domeinId);
    }

    public function getTypeEigDomeinCrossreferenceTypeEigPaginas($typeEigDomeinId) {
        return self::$_xmlrpcClient->call('dao.getTypeEigDomeinCrossreferenceTypeEigPaginas', $typeEigDomeinId);
    }

    /**
     *
     * @param string $referenceName null (default) of referenceTableClass b.v. Type_Eig_Domein
     * @return array met
     *          keys: de kolom naam waarvoor deze id&naam array is aangemaakt.
     *                  b.v. type_eig_domein_id
     *          values: de id&naam array voor deze referentie
     *
     */
    public function getReferenceIdNaamArray($referenceName = null) {
        return self::$_xmlrpcClient->call('dao.getReferenceIdNaamArray', $referenceName);
    }

    public function getTypeEigPagina($options) {
        return self::$_xmlrpcClient->call('dao.getTypeEigPagina', $options);
    }

    public function getEigPagina($options = null) {
        return self::$_xmlrpcClient->call('dao.getEigPagina', $options);
    }

    /**
     *
     * @param int $domeinId
     * @return array
     */
    public function paginas4routes($domeinId) {
        //print_r(self::$_xmlrpcClient->call('test.goed'));
        //print_r(self::$_xmlrpcClient->call('dao.paginas4routes', $domeinId));exit();

        return self::$_xmlrpcClient->call('dao.paginas4routes', (int)$domeinId);
    }

    /**
     * Nou zeg, later moet je dit wel via search doen, dit wordt echt te gek.
     * @param int $mtLinkpoolId
     * @param int $randomizer
     * @param int $aantal
     * @return struct
     */
    public function getBuurlinks($mtLinkpoolId,$randomizer,$aantal) {
        $buurlinks = self::$_xmlrpcClient->call('dao.getBuurlinks', array($mtLinkpoolId,$randomizer,$aantal));
        return $buurlinks;
    }
    /**
     *
     * @param int $mtOnderewerpId
     * @return struct
     */
    public function getOnderwerpLinks($mtOnderewerpId) {
        try {
            $onderwerpLinks = self::$_xmlrpcClient->call('dao.getOnderwerpLinks', $mtOnderewerpId);
        } catch (Zend_XmlRpc_Exception $e) {
            echo $e->getCode().': '.$e->getMessage()."\n";
            print_r(self::$_xmlrpcClient->getLastRequest());
            print_r(self::$_xmlrpcClient->getLastResponse());
            exit();
        } catch (Zend_XmlRpc_Fault $e) {
            echo $e->getCode().': '.$e->getMessage()."\n";
            print_r(self::$_xmlrpcClient->getLastRequest());
            print_r(self::$_xmlrpcClient->getLastResponse());
            exit();
        }

        return $onderwerpLinks;
    }

}

