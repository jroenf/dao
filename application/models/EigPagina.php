<?php

class Model_EigPagina extends Model_Abstract{

    protected $_id;

    protected $_eig_domein_id;
    /**
     *
     * @var Model_EigDomein
     */
    protected $_reference_eig_domein;
    
    protected $_naam;

    protected $_title;

    protected $_description;

    protected $_keywords;

    protected $_h1;

    protected $_p1;

    protected $_h2a;

    protected $_p2a;

    protected $_h2b;

    protected $_p2b;

    protected $_link_tekst;

    protected $_link_title;

    protected $_navigatie_tekst;

    protected $_url_slug;

    protected $_url_absolute;

    protected $_url_full;

    protected $_volgorde;

    protected $_parent_pagina_id;

    protected $_lft;

    protected $_rgt;

    protected $_mt_onderwerp_id;

    protected $_type_eig_pagina_id;

    protected $_online;

    protected $_is_homepage;

    protected $_datum_online;

    protected $_datum_start;

    protected $_content_id;
    

    /**
     *
     * @var Model_Pack_EigNamedsegment
     */
    protected $_dependent_eig_namedsegment;

    protected $_datum_update;
    /**
     *
     * @var Zend_Db_Table_Row
     */
    protected $_tablerow;
    /**
     * Het type van deze pagina
     * @var Model_Domein_Type_Eig_Pagina
     */
    protected $_reference_type_eig_pagina;

    protected $_reference_content;

    protected $_tabledefinitionNaam = 'Eig_Pagina';
    /**
     *
     * @var Model_EigUnit
     */
    protected $_content_unit;
    /**
     *
     * @var Model_EigElement
     */
    protected $_content_element;

    protected $_content;

    /**
     * Dit kan alleen bij pagina's die contentPage 'zijn',
     * Het maken van aparte typen pagina's staat even voor later op het programma.
     * @return Model_EigUnit
     */
    public function getContentUnit() {
        if (!$this->getReferenceTypeEigPagina()->isContentpage()) {
            return false;
        }
        if ($this->_content_unit == null) {
            $this->_setContentUnit();
        }
        return $this->_content_unit;
    }
    /**
     * Dit kan alleen bij pagina's die contentPage 'zijn',
     * Het maken van aparte typen pagina's staat even voor later op het programma.
     */
    protected function _setContentUnit() {
        $this->_content_unit =
            $this->getDefaultMapper()->searchEigUnit(array('where'=>array(
                'eig_pagina_id'=>$this->getId(),
                'mt_namedsegment_id'=>$this->getReferenceTypeEigPagina()->getContentNamedsegmentId(),
                'type_eig_unit_id'=>$this->getReferenceTypeEigPagina()->getTypeContentUnitId(),
            )));
    }
    /**
     * Dit kan alleen bij pagina's die contentPage 'zijn',
     * Het maken van aparte typen pagina's staat even voor later op het programma.
     * @return Model_EigElement
     */
    public function getContentElement() {
        if (!$this->getReferenceTypeEigPagina()->isContentpage()) {
            return false;
        }
        if ($this->_content_element == null) {
            $this->_setContentElement();
        }
        return $this->_content_element;
    }
    /**
     * Dit kan alleen bij pagina's die contentPage 'zijn',
     * Het maken van aparte typen pagina's staat even voor later op het programma.
     */
    public function _setContentElement() {
        $this->_content_element =
                $this->getDefaultMapper()->searchEigElement(array(
                    'where'=>array(
                        'eig_unit_id'=>$this->getContentUnit()->getId(),
                        'content_id'=>$this->getContentId(),
                        'type_eig_element_id'=>$this->getReferenceTypeEigPagina()->getTypeContentElementId(),
                    )
                ));
    }

    /**
     * Dit kan alleen bij pagina's die contentPage 'zijn',
     * Het maken van aparte typen pagina's staat even voor later op het programma.
     * @return <type>
     */
    public function getContent() {
        if (!$this->getReferenceTypeEigPagina()->isContentpage()) {
            return false;
        }
        if ($this->_content == null) {
            $this->_setContent();
        }
        return $this->_content;
    }

    protected function _setContent() {
        $this->_content = $this->getContentElement()->getReferenceContent();
    }


    public function valuesArray() {
        $values = array();
        $values['id'] = $this->_id;
        $values['eig_domein_id'] = $this->_eig_domein_id;
        $values['naam'] = $this->_naam;
        $values['title'] = $this->_title;
        $values['description'] = $this->_description;
        $values['keywords'] = $this->_keywords;
        $values['h1'] = $this->_h1;
        $values['p1'] = $this->_p1;
        $values['h2a'] = $this->_h2a;
        $values['p2a'] = $this->_p2a;
        $values['h2b'] = $this->_h2b;
        $values['p2b'] = $this->_p2b;
        $values['link_tekst'] = $this->_link_tekst;
        $values['link_title'] = $this->_link_title;
        $values['navigatie_tekst'] = $this->_navigatie_tekst;
        $values['url_slug'] = $this->_generateUrlSlug();
        $values['url_absolute'] = $this->_generateUrlFull();
        $values['url_full'] = $this->_generateUrlFull();
        $values['volgorde'] = $this->_volgorde;
        $values['parent_pagina_id'] = $this->_parent_pagina_id;
        $values['lft'] = $this->_lft;
        $values['rgt'] = $this->_rgt;
        $values['mt_onderwerp_id'] = $this->_mt_onderwerp_id;
        $values['type_eig_pagina_id'] = $this->_type_eig_pagina_id;
        $values['online'] = $this->_online;
        $values['is_homepage'] = $this->_is_homepage;
        $values['datum_online'] = $this->_datum_online;
        $values['datum_start'] = $this->_datum_start;
        $values['datum_update'] = $this->_datum_update;
        return $values;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    public function getId() {
        return $this->_id;
    }

    public function setEigDomeinId($eigDomeinId) {
        $this->_eig_domein_id = $eigDomeinId;
    }

    public function getEigDomeinId() {
        return $this->_eig_domein_id;
    }

    public function getEigDomein() {
        return $this->getMapper()->findEigDomein($this->getEigDomeinId());
    }

    public function setNaam($naam) {
        $this->_naam = $naam;
    }

    public function getNaam() {
        return $this->_naam;
    }

    public function setTitle($title) {
        $this->_title = $title;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setKeywords($keywords) {
        $this->_keywords = $keywords;
    }

    public function getKeywords() {
        return $this->_keywords;
    }

    public function setH1($h1) {
        $this->_h1 = $h1;
    }

    public function getH1() {
        return $this->_h1;
    }

    public function setP1($p1) {
        $this->_p1 = $p1;
    }

    public function getP1() {
        return $this->_p1;
    }

    public function setH2a($h2a) {
        $this->_h2a = $h2a;
    }

    public function getH2a() {
        return $this->_h2a;
    }

    public function setP2a($p2a) {
        $this->_p2a = $p2a;
    }

    public function getP2a() {
        return $this->_p2a;
    }

    public function setH2b($h2b) {
        $this->_h2b = $h2b;
    }

    public function getH2b() {
        return $this->_h2b;
    }

    public function setP2b($p2b) {
        $this->_p2b = $p2b;
    }

    public function getP2b() {
        return $this->_p2b;
    }

    public function setLinkTekst($link_tekst) {
        $this->_link_tekst = $link_tekst;
    }

    public function getLinkTekst() {
        if (is_string($this->_link_tekst)) {
            $linktekst = ucfirst(strtolower($this->_link_tekst));
        } else {
            $linktekst = $this->_link_tekst;
        }
        return $linktekst;
    }

    public function setLinkTitle($link_title) {
        $this->_link_title = $link_title;
    }

    public function getLinkTitle() {
        return $this->_link_title;
    }

    public function setNavigatieTekst($navigatie_tekst) {
        $this->_navigatie_tekst = $navigatie_tekst;
    }

    public function getNavigatieTekst() {
        return $this->_navigatie_tekst;
    }

    public function setUrlSlug($url_slug) {
        $this->_url_slug = $url_slug;
    }

    public function getUrlSlug() {
        if ($this->_url_slug === null) {
            $this->_generateUrlSlug();
        }
        return $this->_url_slug;
    }

    protected function _generateUrlSlug() {
        $urlSlug = null;
        if ($this->_naam !== null) {
            if ($this->_naam === 'index') {
                $urlSlug = '';
            } else {
                $urlSlugFilter = new Roen_Filter_UrlSlug();
                $urlSlug = $urlSlugFilter->filter($this->_naam);
            }
        } else {
            throw new Zend_Exception('In '.__CLASS__.' met '.__METHOD__.' geen succes');
        }
        $this->_url_slug = $urlSlug;
        return $urlSlug;
    }

    public function setUrlAbsolute($url_absolute) {
        $this->_url_absolute = $url_absolute;
    }

    public function getUrlAbsolute() {
        if ($this->_url_absolute === null) {
            $this->_generateUrlAbsolute();
        }
        return $this->_url_absolute;
    }

    protected function _generateUrlAbsolute() {
        $urlAbsolute = null;
        if ($this->_naam !== null) {
            if ($this->_naam === 'index') {
                $urlAbsolute = '/';
            } elseif($this->_parent_pagina_id !== null) {
                $parentPagina = $this->getDefaultMapper()->findEigPagina($this->_parent_pagina_id);
                $parentUrlAbs = $parentPagina->getUrlAbsolute();
                $urlAbsolute = ($parentUrlAbs === '/' ? '' : $parentUrlAbs)
                        .'/'.$this->_generateUrlSlug();
            }
        }
        if ($urlAbsolute === null) {
            throw new Zend_Exception('In '.__CLASS__.' met '.__METHOD__.' geen succes');
        }
        $this->_url_absolute = $urlAbsolute;
        return $urlAbsolute;
    }

    public function setUrlFull($url_full) {
        $this->_url_full = $url_full;
    }

    public function getUrlFull() {
        if ($this->_url_full === null) {
            $this->_generateUrlFull();
        }
        return $this->_url_full;
    }

    protected function _generateUrlFull() {
        $urlFull = null;
        if ($this->_eig_domein_id !== null) {
            $eigDomein = $this->getReferenceEigDomein();
            $urlFull = 'http://'.($eigDomein->getWww()?'www.':'').
                    $eigDomein->getNaam().
                    $this->_generateUrlAbsolute();//Klopt: index heeft / de rest begint ook met /
        }
        if ($urlFull === null) {
            throw new Zend_Exception('In '.__CLASS__.' met '.__METHOD__.' geen succes');
        }
        $this->_url_full = $urlFull;
        return $urlFull;
    }

    public function setVolgorde($volgorde) {
        $this->_volgorde = $volgorde;
    }

    public function getVolgorde() {
        return $this->_volgorde;
    }

    public function setParentPaginaId($parent_pagina_id) {
        $this->_parent_pagina_id = $parent_pagina_id;
    }

    public function getParentPaginaId() {
        return $this->_parent_pagina_id;
    }

    public function setLft($lft) {
        $this->_lft = $lft;
    }

    public function getLft() {
        return $this->_lft;
    }

    public function setRgt($rgt) {
        $this->_rgt = $rgt;
    }

    public function getRgt() {
        return $this->_rgt;
    }
    public function setMtOnderwerpId($mt_onderwerp_id) {
        $this->_mt_onderwerp_id = $mt_onderwerp_id;
    }

    public function getMtOnderwerpId() {
        return $this->_mt_onderwerp_id;
    }

    public function setTypeEigPaginaId($type_eig_pagina_id) {
        $this->_type_eig_pagina_id = $type_eig_pagina_id;
    }

    public function getTypeEigPaginaId() {
        return $this->_type_eig_pagina_id;
    }

    public function setOnline($online) {
        $this->_online = $online;
    }

    public function getOnline() {
        return $this->_online;
    }

    public function setIsHomepage ($is_homepage) {
        $this->_is_homepage = $is_homepage;
    }

    public function getIsHomepage(){
        return $this->_is_homepage;
    }

    public function setDatumOnline($datum_online) {
        $this->_datum_online = $datum_online;
    }

    public function getDatumOnline() {
        return $this->_datum_online;
    }

    public function setDatumStart($datum_start) {
        $this->_datum_start = $datum_start;
    }

    public function getDatumStart() {
        return $this->_datum_start;
    }

    public function getDatumUpdate() {
        return $this->_datum_update;
    }

    /**
     *
     * @return Model_EigDomein
     */
    public function  getReferenceEigDomein() {
        if ($this->_reference_eig_domein == null) {
            $this->_reference_eig_domein = 
                    $this->getDefaultMapper()
                    ->findEigDomein($this->_eig_domein_id);
        }
        return $this->_reference_eig_domein;
    }

    public function navigatielink($options = array()) {
        $link = '<a href="'.$this->_url_full.'" title="'.$this->_link_title.'"';
        foreach ($options as $attr => $val) {
            $link .= ' '.$attr.'="'.$val.'"';
        }
        $link .= '>'.$this->_naam.'</a>';
        return $link;
    }

    public function setTablerow($tablerow) {
        $this->_tablerow = $tablerow;
    }


    /**
     *
     * @return Zend_Db_Table_Row
     */
    public function getTablerow() {
        if ($this->_tablerow == null) {
            throw new Zend_Exception('Er is helaas geen tablerow in '.__CLASS__);
        } else {
            return $this->_tablerow;
        }
    }
    /**
     * Gebruikt lft en rgt om alle directe children
     * op te vragen. Gesorteerd op volgorde, max eerst, die wordt gereturned
     * @return array
     */
    public function getChildPages() {
        //query die alle directe children opvraagd
        
        $query = "  SELECT
                        node.id,
                        node.naam,
                        node.volgorde,
                        (COUNT(parent.id) - (sub_tree.depth + 1)) AS depth
                    FROM
                        eig_pagina AS node,
                        eig_pagina AS parent,
                        eig_pagina AS sub_parent,
                        (
                            SELECT  node.id,
                                    node.naam,
                                    node.eig_domein_id,
                                    (COUNT(parent.naam) - 1) AS depth
                            FROM    eig_pagina AS node,
                                    eig_pagina AS parent
                            WHERE   node.lft BETWEEN parent.lft AND parent.rgt
                                    AND node.id = :id
                                    AND parent.eig_domein_id = node.eig_domein_id
                            GROUP BY node.id
                            ORDER BY node.lft
                        ) AS sub_tree
                    WHERE
                            node.lft BETWEEN parent.lft AND parent.rgt
                        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
                        AND sub_parent.id = sub_tree.id
                        AND node.eig_domein_id          = sub_tree.eig_domein_id
                        AND parent.eig_domein_id        = sub_tree.eig_domein_id"
                    //  sub_parent.eig_domein_id hoeft niet, die heeft al de juiste
                    //  eig_domein_id door sub_parent.id = sub_tree.id
                        ."
                    GROUP BY node.id
                    HAVING depth = 1
                    ORDER BY node.lft;";//doe 'depth <=1' voor parent ook er bij
                                            //DESC toegevoegd voor max volgorde
        $db = $this->_mapper->getDataAccessObject()->getDb();
        //$result = $db->fetchAll($query,array('id'=>$this->getId(),'eig_domein_id'=>$this->getEigDomeinId()));
        $result = $db->fetchAll($query,array(':id'=>$this->getId()));//,':eig_domein_id'=>$this->getEigDomeinId()));
        return $result;
    }

    public function getChildPagesMaxVolgorde() {
        $childPages = $this->getChildPages();
        if (empty($childpages)) {
            return 0;
        } else {
            $childPagesOmgekeerd = array_reverse($childPages);
            return $childPagesOmgekeerd[0]['volgorde'];
        }
    }

    public function getChildFilmPaginas() {
        $childFilmPaginaPack = $this->getDefaultMapper()
                ->getEigPaginaDependentChildPaginaPack($this->_id,array(
                    'where'=>array(
                        'type_eig_pagina_id'=>3
                    )));
        return $childFilmPaginaPack;
    }

    /**
     *
     * @return Model_TypeEigPagina
     */
    public function getReferenceTypeEigPagina() {
        if ($this->_reference_type_eig_pagina == null) {
            $this->_reference_type_eig_pagina = $this->getDefaultMapper()
                    ->findTypeEigPagina($this->_type_eig_pagina_id);
        }
        return $this->_reference_type_eig_pagina;
    }
    /**
     *
     * @return Model_Pack_EigNamedsegment
     */
    public function getDependentEigNamedsegment() {
        if ($this->_dependent_eig_namedsegment == null) {
            $this->setDependentEigNamedsegment();
        }
        return $this->_dependent_eig_namedsegment;
    }

    public function setDependentEigNamedsegment() {
        $typeEigPagina = $this->getReferenceTypeEigPagina();
        $typeNamedSegmentPack = $typeEigPagina->getDependentTypeNamedsegment();
        //print_r($typeNamedSegmentPack);exit();
        //print_r($typeNamedSegmentPack);exit();
        $values = array();
        foreach ($typeNamedSegmentPack as $typeNamedsegment) {
            $curMtNamedSegmentId = $typeNamedsegment->getMtNamedsegmentId();
            $volgorde = $typeNamedsegment->getVolgorde();
            $naam = $typeNamedsegment->getNaam();
            $values[] = array(
                'eig_pagina_id'         =>  $this->getId(),
                'mt_namedsegment_id'    =>  $curMtNamedSegmentId,
                'volgorde'              =>  $volgorde,
                'naam'                  =>  $naam,
                );
        }
        $this->_dependent_eig_namedsegment =
                new Model_Pack_EigNamedsegment($values,$this->getDefaultMapper());
    }

    public function getDependentEigUnit() {
        if ($this->_dependent_eig_unit == null) {
            $this->setDependentEigUnit();
        }
        return $this->_dependent_eig_unit;
    }


    public function setDependentEigUnit() {
        $this->_dependent_eig_unit = $this->getDefaultMapper()
                ->getEigPaginaDependentEigUnit();
        return $this;
    }
    
    public function typeChildPaginaIsValid($typeChildPaginaId) {
        //print_r($this
        //    ->getReferenceTypeEigPagina()->getId());exit();
        $typeChildPaginaPack = $this
            ->getReferenceTypeEigPagina()
                ->getDependentTypeChildPagina(array(
                    'where' =>  array(
                        //'type_eig_pagina_id'    =>  $typeChildPaginaId
                        'subpagina_id'    =>  $typeChildPaginaId
                    )
                ));
        //print_r($typeChildPaginaPack);exit();
        $typeChildPagina = $typeChildPaginaPack->current();
        if ($typeChildPagina->getId() == $typeChildPaginaId) {
            return true;
        }
        return false;
    }

    public function getContentId() {
        return $this->_content_id;
    }

    public function setContentId($_content_id) {
        $this->_content_id = $_content_id;
    }

    //andere ideeeen zijn
    //getSiblingsNonContentpage
    //getSiblingsContentPageWithTypeContent
    //Hetzelfde als deze en ideeeen maar dan children i.p.v. sibling
    /**
     *
     * @return Model_Pack_EigPagina
     */
    public function getSiblingsSameTypeEigPagina() {
       
        $mapper = $this->getDefaultMapper();
        $options = array(
            'where'=>array(
                'parent_pagina_id'=>$this->getParentPaginaId(),
                'type_eig_pagina_id'=>$this->getTypeEigPaginaId(),
                'online'=>1
                ),
            'order' => 'volgorde'
                );
        $siblingsPack = $mapper->getPackEigPagina($options);
        return $siblingsPack;
    }

    public function getSiblings() {
        $mapper = $this->getDefaultMapper();
        $options = array(
            'where'=>array(
                'parent-pagina-id'=>$this->getParentPaginaId(),
                ),
            'order' => 'volgorde'
                );
        $siblingsPack = $mapper->getPackEigPagina($options);
        return $siblingsPack;
    }

    public function renderNavLink() {
        $navLink = '<a href="'
            .$this->getView()->baseUrl($this->getUrlAbsolute())
            .'"';
        $title = ($this->getLinkTitle())?' title="'.$this->getLinkTitle().'"':'';
        $navLink .= $title;
        $navLink .= '>'
            //ToDo: linktekst in smallcase met ucfirst in db opslaan.
            .$this->getLinkTekst()
            .'</a>';
        return $navLink;
    }



}