<?php
/**
 * 
 */
class Model_Mapper_Default extends Model_Mapper_Abstract {
    
    public function find($id) {}

    public function fetchAll() {
    }

    public function packFactory($name) {

        switch ($name) {
            case 'Eig_Domein':
                $pack = new Model_Pack_EigDomein();
                break;
            case 'Eig_Pagina':
                $pack = new Model_Pack_EigPagina();
                break;
            default:
                break;
        }
        return $pack;
    }

    public function searchEigDomein($options) {
        $resultArr = $this->getDataAccessObject()->search('Eig_Domein',$options);
        if (!is_array((array)$resultArr)) {
            throw new Zend_Exception('Je kan nix met het resultaat van '.__METHOD__.' in '.__CLASS__);
        }
        return new Model_EigDomein($resultArr,$this);
    }

    /**
     *
     * @param integer $domeinId
     * @return Model_Pack_EigPagina
     */
    public function getEigDomeinDependentEigPaginaPack($domeinId) {
        $paginaArrays = $this->getDataAccessObject()->getEigDomeinDependentEigPaginas($domeinId);
        return new Model_Pack_EigPagina($paginaArrays,$this);
    }

    public function getEigDomeinReferenceTypeEigDomein($eigDomeinId) {
        $values = $this->getDataAccessObject()
                ->getEigDomeinReferenceTypeEigDomein($eigDomeinId);
        return new Model_TypeEigDomein($values, $this);
    }

    public function getEigPaginaReferenceTypeEigPagina($eigDomeinId) {
        $values = $this->getDataAccessObject()
                ->getEigPaginaReferenceTypeEigPagina($eigDomeinId);
        return new Model_TypeEigPagina($values, $this);
    }

    public function getTypeEigDomeinCrossreferenceTypeEigPaginaPack($typeEigDomeinId) {
        $typeEigPaginaArrays = $this->_dataAccessObject
                ->getTypeEigDomeinCrossreferenceTypeEigPaginas($typeEigDomeinId);
        return new Model_Pack_TypeEigPagina($typeEigPaginaArrays, $this);
    }

    public function fetchAllEigDomein() {
        $domeinenArray = $this->getDataAccessObject()->fetchAll('Eig_Domein');
        $eigDomeinPack = new Model_Pack_EigDomein($domeinenArray,$this);
        return $eigDomeinPack;
    }

    /**
     * Decrepaded. Wordt vervangen door getPackTypeEigPaginas met $options
     * $options is een array met iig 'where' => array('field' => $value)
     * Kijkt bij een domein-type(id) welke pagina-types allemaal mogelijk zijn.
     * --
     * Inmiddels weer anders. Om de relaties in beeld te houden werk ik nu met
     * functies als getTypeEigDomeinCrossreferenceTypeEigPaginaPack()
     * je hebt 3 relaties: Crossreference, Dependent en Reference
     * @param integer $typeEigDomeinId
     * @return Model_Pack_TypeEigPagina
     */
    public function getPackTypeEigPaginasByTypeEigDomeinId($typeEigDomeinId) {
        $packTypeEigPage = new Model_Pack_TypeEigPagina();
        $typeArrays = $this->_dataAccessObject->getTypeEigPagesByTypeEigDomeinId($typeEigDomeinId);
        foreach ($typeArras as $typeArray) {
            $typeEigPagina = new Model_TypeEigPagina($typeArray, $this->getDataAccessObject());
            $packTypeEigPage->add($typeEigPagina);
        }
        return $packTypeEigPage;
    }

    public function getPackTypeEigPagina($options = null) {
        $packTypeEigPage = new Model_Pack_TypeEigPagina();
        $typeArrays = $this->_dataAccessObject->getTypeEigPagina($options);
        foreach ($typeArrays as $typeArray) {
            $typeEigPagina = new Model_TypeEigPagina($typeArray, $this->getDataAccessObject());
            $packTypeEigPage->add($typeEigPagina);
        }
        return $packTypeEigPage;
    }

    public function getPackEigPagina($options = null) {
        $results = $this->getDataAccessObject()->getEigPagina($options);
        return new Model_Pack_EigPagina($results,$this);
    }


    public function fetchAllEigPagina($options = null) {
        $results = $this->getDataAccessObject()->fetchAll('Eig_Pagina');
        if (!is_array((array)$results)) {
            throw new Zend_Exception('Je kan nix met het resultaat van '.__METHOD__.' in '.__CLASS__);
        }
        return new Model_Pack_EigPagina($results);
    }

    public function findEigDomein($id) {
        $resultArray = $this->getDataAccessObject()->find('Eig_Domein',$id);
        if (is_array((array)$resultArray)) {
            return new Model_EigDomein($resultArray,$this);
        }
        throw new Zend_Exception('Je kan nix met het resultaat van '.__METHOD__.' in '.__CLASS__);
    }

    /**
     *
     * @param int $id
     * @return Model_EigPagina
     */
    public function findEigPagina($id) {
        $resultArray = $this->getDataAccessObject()->find('Eig_Pagina',$id);
        if (is_array((array)$resultArray)) {
            return new Model_EigPagina($resultArray,$this);
        }
        throw new Zend_Exception('Je kan nix met het resultaat van '.__METHOD__.' in '.__CLASS__);
    }

    public function findTypeEigPagina($id) {
        $resultArray = $this->getDataAccessObject()->find('Type_Eig_Pagina',$id);
        if (is_array((array)$resultArray)) {
            return new Model_TypeEigPagina($resultArray,$this);
        }
        throw new Zend_Exception('Je kan nix met het resultaat van '.__METHOD__.' in '.__CLASS__);
    }

    public function saveEigDomein($values,$id = null) {
        if ($id == null) {
            //randomizer
            $id = $this->getDataAccessObject()->insert($values,'Eig_Domein');
        } else {
            //naam is uniek, dus verwijderen
            $id = $this->getDataAccessObject()->update($values,$id,'Eig_Domein');
        }
        return $id;
    }

    public function saveEigPagina($values,$id = null) {
        if ($id == null) {
            $id = $this->getDataAccessObject()->insert($values,'Eig_Pagina');
        } else {
            if (array_key_exists('naam', $values)) {
                unset ($values['naam']);
            }
            $id = $this->getDataAccessObject()->update($values,$id,'Eig_Pagina');
        }
        return $id;
    }

    public function getTypeEigPaginaDependentTypeChildPaginaPack($typeEigPaginaId,$options = array()) {
        $typeChildPages =
            $this->getDataAccessObject()
                ->getTypeEigPaginaCrossreferenceTypeChildPagina($typeEigPaginaId,$options);
        return new Model_Pack_TypeEigPagina($typeChildPages, $this);
    }

    public function getEigPaginaDependentChildPaginaPack($eigPaginaId,$options = null) {
        $childPages =
            $this->getDataAccessObject()
                ->getEigPaginaDependentChildPagina($eigPaginaId,$options);
        return new Model_Pack_EigPagina($childPages, $this);
    }

    /**
     * Nou zeg, later moet je dit wel via search doen, dit wordt echt te gek.
     * @param int $mtLinkpoolId
     * @param int $randomizer
     * @param int $aantal
     * @return struct
     */
    public function getBuurlinks($mtLinkpoolId,$randomizer,$aantal) {
        $buurlinks = $this->getDataAccessObject()
                ->getBuurlinks($mtLinkpoolId,$randomizer,$aantal);
        return $buurlinks;
    }
    /**
     *
     * @param int $mtOnderwerpId
     * @return struct
     */
    public function getOnderwerpLinks($mtOnderwerpId) {
        $onderwerpLinks = $this->getDataAccessObject()
                ->getOnderwerpLinks($mtOnderwerpId);
        return $onderwerpLinks;
    }


}

