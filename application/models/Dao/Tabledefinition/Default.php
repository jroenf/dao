<?php
class Model_Dao_Tabledefinition_Default extends Model_Dao_Tabledefinition_Abstract {

    protected $_defaultdbname = 'wmcentral';

    /**
     *
     * @param string $tablename
     * @param struct $options
     * @return struct
     */
    public function search($tablename,$options) {
        $table = $this->getTable($tablename);
        $select = $table->select();
        if (is_array($options)) {
            if (array_key_exists('where', $options)) {
                foreach ($options['where'] as $field => $value) {
                    $select->where($field.' = ?', $value);
                }
            }
        }
        $rowset = $table->fetchAll($select);
        if ($rowset->count() != 1) {
            throw new Zend_Exception('Er is niet één enkele waarde gevonden met functie '.__METHOD__.' in '.__CLASS__);
        }

        return $rowset->current()->toArray();
    }

    public function findIdNaamArray($tablename) {
        $table = $this->getTable($tablename);
        $select = $table->select()->columns(array('id','naam'));
        return $this->getDb()->fetchAssoc($select);
    }
    
    /**
     *
     * @param integer $domeinId
     * @return array als er niets gevonden is is dit een lege array 
     */
    public function getEigDomeinDependentEigPaginas($domeinId) {
        $domeinRowset = $this->getTable('Eig_Domein')->find($domeinId);
        if ($domeinRowset->count() != 1) {
            throw new Zend_Exception('Huh, ik kan in '.__CLASS__.' met '.__METHOD__.' niet eens een EigDomein vinden');
        }
        $pageArrays = array();
        $pageRows = $domeinRowset->current()->findDependentRowset('Eig_Pagina');
        if ($pageRows->count() >= 1) {
            $pageArrays = $pageRows->toArray();
        }
        //print_r($pageArrays);exit();
        return $pageArrays;
    }

    public function getEigDomeinReferenceTypeEigDomein($domeinId) {
         $domeinRowset = $this->getTable('Eig_Domein')->find($domeinId);
         if ($domeinRowset->count() != 1) {
             throw new Zend_Exception('Huh, ik kan in '.__CLASS__.' met '.__METHOD__.' niet eens een EigDomein vinden');
         }
         $typeEigDomeinRow = $domeinRowset->current()->findParentRow('Type_Eig_Domein');
         return $typeEigDomeinRow->toArray();
     }
     
     public function getEigPagina($options = null) {
        $table = $this->getTable('Eig_Pagina');
        $select = $table->select();
        if ($options !== null) {
            if (array_key_exists('where', $options)) {
                foreach ($options ['where'] as $field=> $value) {
                    $select->where($field.' = ?',$value);
                }
            }
            if (array_key_exists('order',$options)) {
                if (is_array($options['order'])) {
                    foreach ($options['order'] as $field) {
                        $select->order($field);
                    }
                } else if(is_string($options['order'])) {
                    $select->order($options['order']);
                }
            }
        }
        $eigPaginas = array();
        $rowset = $table->fetchAll($select);
        if ($rowset->count() >= 1) {
            $eigPaginas = $rowset->toArray();
        }
        return $eigPaginas;
    }
    
    /**
     * Deze functie is gemaakt voor bij fetchReferenceIdNaamArray omdat dezelfde
     * code stond in een foreach loop en ook daar buiten.
     * @param string $refTableClass
     * @return array met keys id en values name
     */
    protected function _getSingleReferenceIdNaamArray($refTableClass) {
        $table = $this->getTable($refTableClass);
        $select = $table->select()->from($table,array('id','naam'));
        return $table->getAdapter()->fetchPairs($select);
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
        $config = $this->getTableDefinition()->getTableConfig('Eig_Domein');
        $referenceIdNaamArray = array();
        if ($referenceName == null) {
            foreach ($config['referenceMap'] as $referenceName => $referenceSetting) {
                $referenceIdNaamArray[$referenceSetting['refTableClass']] =
                    $this->_getSingleReferenceIdNaamArray($referenceSetting['refTableClass']);
            }
        } elseif (array_key_exists($referenceName, $config['referenceMap'])) {
            $referenceIdNaamArray[$config['referenceMap'][$referenceName]['refTableClass']] =
               $this->_getSingleReferenceIdNaamArray($referenceName);
        }
        return $referenceIdNaamArray;
        /*  $config['referenceMap'] ziet er als volgt uit:
         * [referenceMap] => Array
        (
            [Type_Eig_Domein] => Array
                (
                    [columns] => type_eig_domein_id
                    [refTableClass] => Type_Eig_Domein
                    [refColumns] => id
                )

            [Mt_Hosting] => Array
                (
                    [columns] => mt_hosting_id
                    [refTableClass] => Mt_Hosting
                    [refColumns] => id
                )

            [Mt_Linkpool] => Array
                (
                    [columns] => mt_linkpool_id
                    [refTableClass] => Mt_Linkpool
                    [refColumns] => id
                )

            [Mt_Onderwerp] => Array
                (
                    [columns] => mt_onderwerp_id
                    [refTableClass] => Mt_Onderwerp
                    [refColumns] => id
                )

         */
    }

    public function getTypeEigPagina($options) {
        $table = $this->getTable('Type_Eig_Pagina');
        $select = $table->select();
        if (array_key_exists('where', $options)) {
            foreach ($options ['where'] as $field=> $value) {
                $select->where($field.' = ?',$value);
            }
        }
        return $table->fetchAll($select)->toArray();
    }

    

    public function getTypeEigPaginaCrossreferenceTypeChildPagina($typeEigPaginaId,$options = array()) {
        $table = $this->getTable('Type_Eig_Pagina');
        $typeEigPaginaRow = $table->find($typeEigPaginaId)->current();
        $select = $table->select();
        if (array_key_exists('where', $options)) {
            foreach ($options['where'] as $field => $value) {
                $select->where($field.' = ?', $value);
            }
        }
        $typeChildPaginaRowset = $typeEigPaginaRow
            ->findManyToManyRowset(
                    'Type_Eig_Pagina',
                    'Type_Eig_Pagina_X_Subpagina',
                    'Type_Eig_Pagina',
                    'Subpagina',
                    $select
                    );
        //    ->findDependentRowset('Type_Eig_Pagina_X_Subpagina','Type_Eig_Pagina');
        //$nogEenRowset = $typeChildPaginaRowset->
        $typeChildPaginas = array();
        if ($typeChildPaginaRowset->count() >= 1) {
            $typeChildPaginas = $typeChildPaginaRowset->toArray();
        }
        return $typeChildPaginas;
    }
    /**
     * Let op:
     * Deze functie selecteert alleen de DIRECT children van een pagina
     * @param int $eigPaginaId
     * @return array
     */
    public function getEigPaginaDependentChildPagina($eigPaginaId,$options = null) {
        $eigPagTable = $this->getTable('Parent_Pagina');
        $eigPaginaRow = $eigPagTable->find($eigPaginaId)->current();

        $select = $eigPagTable->select();
        if (is_array($options)) {
            if (array_key_exists('where', $options)) {
                foreach ($options['where'] as $field => $value) {
                    $select->where($field.' = ?', $value);
                }
            }
        }
        $select->where('content_id IS NOT NULL');
        $childPaginaRowset = $eigPaginaRow
            ->findDependentRowset('Eig_Pagina',null, $select);
        $childPaginas = array();
        if ($childPaginaRowset->count() >= 1) {
            $childPaginas = $childPaginaRowset->toArray();
        }
        //print_r($childPaginas);exit();
        return $childPaginas;
    }

    /**
     *
     * @param int $domeinId
     * @return array
     */
    public function paginas4routes($domeinId) {
        $db = $this->getDb();
        $paginaselect = $db->select()->from(
                array('d'=>'eig_domein'),
                array(
                    'eig-domein_id'=>'d.id',
                    'eig-domein'=>'d.naam'
                    ))
                ->join(
                        array('td'=>'type_eig_domein'),
                        'd.type_eig_domein_id = td.id',
                        array(
                            'type-eig-domein-id'=>'td.id',
                            'type-eig-domein'=>'td.naam'
                            ))
                ->join(
                        array('p'=>'eig_pagina'),
                        'd.id = p.eig_domein_id',
                        array(
                            'eig-pagina-id'=>'p.id',
                            //'eig-pagina'=>'naam',
                            'url-absolute'=>'p.url_absolute',
                            'volgorde'=>'',
                            'parent_pagina_id'=>'p.parent_pagina_id',
                            'navigatie_tekst'=>'p.navigatie_tekst',
                            'link_title'=>'p.link_title',
                        ))
                ->join(
                        array('tp'=>'type_eig_pagina'),
                        'p.type_eig_pagina_id = tp.id',
                        array(
                            'type-eig-pagina-id'=>'tp.id',
                            'type-eig-pagina'=>'tp.naam'
                            ))
                ->where('d.id = '.(int)$domeinId)
                ->where('p.online = ' . 1);
        return $db->fetchAll($paginaselect);
    }
    /**
     * Nou zeg, later moet je dit wel via search doen, dit wordt echt te gek.
     * @param int $mtLinkpoolId
     * @param int $randomizer
     * @param int $aantal
     * @return struct
     */
    public function getBuurlinks($mtLinkpoolId,$randomizer,$aantal) {
        $domeinTable = $this->getTable('Eig_Domein');

        $selection = $domeinTable->select()
                ->where('mt_linkpool_id = ?', $mtLinkpoolId)
                ->where('online = 1')
                ->where('randomizer < ?',$randomizer)
                ->order('randomizer DESC')
                ->limit($aantal);
        $buurlinks = $domeinTable->fetchAll($selection)->toArray();

        if(count($buurlinks) < $aantal){
            $aantalrest = $aantal - count($buurlinks);
            $selection2 = $domeinTable->select()
                ->where('mt_linkpool_id = ?',$mtLinkpoolId)
                ->where('online = 1')
                ->order('randomizer DESC')
                ->limit($aantalrest)
                ;
            $buurrest = $domeinTable->fetchAll($selection2)->toArray();
            $buurlinks = array_merge($buurlinks,$buurrest);
        }
        return $buurlinks;
    }

    /**
     *
     * @param int $mtOnderwerpId
     * @return struct
     */
    public function getOnderwerpLinks($mtOnderwerpId) {
        $db = $this->getDb();
        $selection = $db->select()
                ->from(array('d'=>'eig_domein'),
                        array('naam','link_title','link_tekst','www'))
                ->join(array('l'=>'mt_linkpool'),
                        'd.mt_linkpool_id = l.id',
                        array())//'linkpool'=>'naam'
                ->where('d.online = 1')
                ->where('d.mt_onderwerp_id = '.$mtOnderwerpId)
                ->where('l.naam = ?','eigenste1');
        //echo $selection;exit();
        $onderwerpLinks = $selection->query()->fetchAll();
        return $onderwerpLinks;
    }


}

