<?php
class Model_EigDomein extends Model_Abstract {

    /**
     * @var Model_TypeEigDomein
     */
    protected $_reference_typeEigDomein;

    /**
     *
     * @var Model_Pack_EigPagina
     */
    protected $_dependent_eig_paginas;
    /**
     *
     * @var int
     */
    protected $_id;
    /**
     *
     * @var string
     */
    protected $_naam;
    /**
     *
     * @var string
     */
    protected $_www;
    /**
     *
     * @var string
     */
    protected $_link_tekst;
    /**
     *
     * @var string
     */
    protected $_link_title;
    /**
     *
     * @var string
     */
    protected $_promoinfo;
    /**
     *
     * @var string
     */
    protected $_teller;
    /**
     *
     * @var string
     */
    protected $_teller_kort;
    /**
     *
     * @var int
     */
    protected $_online;
    /**
     *
     * @var string
     */
    protected $_datum_online;
    /**
     *
     * @var string
     */
    protected $_title_kort;
    /**
     *
     * @var int
     */
    protected $_mt_onderwerp_id;
    /**
     *
     * @var int
     */
    protected $_type_eig_domein_id;
    /**
     *
     * @var int
     */
    protected $_mt_linkpool_id;
    /**
     *
     * @var int
     */
    protected $_mt_hosting_id;
    /**
     *
     * @var int
     */
    protected $_mt_server_id;
    /**
     *
     * @var int
     */
    protected $_mt_serveradmin_id;
    /**
     *
     * @var int
     */
    protected $_mt_scripttype_id;
    /**
     *
     * @var int
     */
    protected $_randomizer;
    /**
     *
     * @var mixed string|Zend_Db_Expr
     */
    protected $_datum_start;

    protected $_datum_aangepast;
    /**
     *
     * @var Form_Abstract
     */
    protected $_form;
    /**
     *
     * @var Model_MtLinkpool
     */
    protected $_reference_mt_linkpool;

    /**
     *
     * @return Form_Abstract
     */
    public function getForm() {
        if ($this->_form == null) {
            $this->setForm();
        }
        return $this->_form;
    }

    public function setForm() {
        $this->_form = new Form_EigDomein();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }

    public function setNaam($value) {
        $this->_naam = $value;
    }

    public function getNaam() {
        return $this->_naam;
    }

    public function setWww($value) {
        $this->_www = $value;
    }

    public function getWww() {
        return $this->_www;
    }

    public function setLinkTekst($value) {
        $this->_link_tekst = $value;
    }

    public function getLinkTekst() {
        return $this->_link_tekst;
    }

    public function setLinkTitle($value) {
        $this->_link_title = $value;
    }

    public function getLinkTitle() {
        return $this->_link_title;
    }

    public function setPromoinfo($value) {
        $this->_promoinfo = $value;
    }

    public function getPromoinfo() {
        return $this->_promoinfo;
    }

    public function setTeller($value) {
        $this->_teller = $value;
    }

    public function getTeller() {
        return $this->_teller;
    }

    public function setTellerKort($value) {
        $this->_teller_kort = $value;
    }

    public function getTellerKort() {
        return $this->_teller_kort;
    }

    public function setOnline($value) {
        $this->_online = $value;
    }

    public function getOnline() {
        return $this->_online;
    }

    public function setDatumOnline($value) {
        $this->_datum_online = $value;
    }

    public function getDatumOnline() {
        return $this->_datum_online;
    }

    public function setTitleKort($value) {
        $this->_title_kort = $value;
    }

    public function getTitleKort() {
        return $this->_title_kort;
    }

    public function setMtOnderwerpId($value) {
        $this->_mt_onderwerp_id = $value;
    }

    public function getMtOnderwerpId() {
        return $this->_mt_onderwerp_id;
    }

    public function setTypeEigDomeinId($value) {
        $this->_type_eig_domein_id = $value;
    }

    public function getTypeEigDomeinId() {
        return $this->_type_eig_domein_id;
    }

    public function setMtLinkpoolId($value) {
        $this->_mt_linkpool_id = $value;
    }

    public function getMtLinkpoolId() {
        return $this->_mt_linkpool_id;
    }

    public function setMtHostingId($value) {
        $this->_mt_hosting_id = $value;
    }

    public function getMtHostingId() {
        return $this->_mt_hosting_id;
    }

    public function setMtServerId($value) {
        $this->_mt_server_id = $value;
    }

    public function getMtServerId() {
        return $this->_mt_server_id;
    }

    public function setMtServeradminId($value) {
        $this->_mt_serveradmin_id = $value;
    }

    public function getMtServeradminId() {
        return $this->_mt_serveradmin_id;
    }

    public function setMtScripttypeId($value) {
        $this->_mt_scripttype_id = $value;
    }

    public function getMtScripttypeId() {
        return $this->_mt_scripttype_id;
    }

    public function setRandomizer($value) {
        $this->_randomizer = $value;
    }

    public function getRandomizer() {
        return $this->_randomizer;
    }

    public function setDatumStart($value) {
        $this->_datum_start = $value;
    }

    public function getDatumStart() {
        return $this->_datum_start;
    }

    public function getDatumUpdate() {
            return $this->_datum_aangepast;
    }

    /**
     *
     * @param array $options
     * @return string
     */
    public function standaardlink($options = array()) {
        $url = 'http://'.($this->_www ? 'www.' : '').$this->_naam.'/';//met slash erachter
        $link = '<a href="'.$url.'" title="'.$this->_link_title.'"';
        foreach ($options as $attr => $val) {
            $link .= ' '.$attr.'="'.$val.'"';
        }
        $link .= '>'.$this->_link_tekst.'</a>';
        return $link;
    }

    public function getIndexEnSubPaginas() {
        return $this->getMapper()->getIndexEnSubPaginas($this->_id);
    }

    public function getDependentEigPaginas() {
        if ($this->_dependent_eig_paginas === null) {
            $this->_dependent_eig_paginas = $this->getDefaultMapper()
                    ->getEigDomeinDependentEigPaginaPack($this->_id);
        }
        return $this->_dependent_eig_paginas;
    }
    /**
     *
     * @return Model_EigPagina
     */
    public function getHomepage() {
        $homepage = false;
        $paginas = $this->getDependentEigPaginas();

        foreach ($paginas as $pagina) {
            if($pagina->getIsHomepage()) {
                $homepage = $pagina;
            }
        }
        return $homepage;
    }

    public function hasHomepage() {
        $homepageGevonden = false;
        $paginas = $this->getDependentEigPaginas();
        
        if ($paginas->count() > 0) {
            foreach ($paginas as $pagina) {
                if($pagina->getIsHomepage()) {
                    $homepageGevonden = true;
                }
            }
        }
        return $homepageGevonden;
    }

    public function getHomepageTypeId() {
        $typeEigDomein = $this->getReferenceTypeEigDomein($this->_type_eig_domein_id);
        $typeEigPaginaPack = $typeEigDomein->getCrossreferenceTypeEigPagina();
        $homepageTypeId = '';
        foreach ($typeEigPaginaPack as $typeEigPagina) {
            if ($typeEigPagina->getIsHomepage()) {
                $homepageTypeId = $typeEigPagina->getId();
            }
        }
        return $homepageTypeId;
    }

    /**
     *
     * @return Model_TypeEigDomein
     */
    public function getReferenceTypeEigDomein() {
        if ($this->_reference_typeEigDomein == null) {
            $this->_reference_typeEigDomein = $this->getDefaultMapper()
                    ->getEigDomeinReferenceTypeEigDomein($this->_id);
        }
        return $this->_reference_typeEigDomein;
    }

    /**
     *
     * @return Model_MtLinkpool
     */
    public function getReferenceMtLinkpool() {
        if ($this->_reference_mt_linkpool == null) {
            $this->_reference_mt_linkpool = $this->getDefaultMapper()
                    ->findMtLinkpool($this->_mt_linkpool_id);
        }
        return $this->_reference_mt_linkpool;
    }

    public function creeerHomePage() {
        $mapper = $this->getDefaultMapper();
        if (count($this->getDependentEigPaginas()) == 0) {
            $possiblePageTypes = $mapper->getPackTypeEigPaginaByTypeEigDomeinId($this->_type_eig_domein_id);
            $homePageTypeId = 0;
            foreach ($possiblePageTypes as $possiblePageType) {
                if ($possiblePageType->is_homepage) {
                    $homePageTypeId = $possiblePageType->id;
                }
            }
            if ($homePageTypeId != 0) {
                $paginaValues = array(
                    'naam'=>'index',
                    'url_slug'=>'',
                    'url_absolute'=>'/',
                    'url_full'=>$this->getUrlFull(),
                    'navigatie_tekst'=>'home',
                    'is_homepage'=>1,
                    'mt_onderwerp_id'=>$this->getMtOnderwerpId(),
                    'type_eig_pagina_id'=>$homePageTypeId,
                    'eig_domein_id'=>$this->getId(),
                    'parent_pagina_id'=>null,
                    'volgorde'      =>null,
                    'link_tekst'=>$this->getLinkTekst(),
                    'link_title'=>$this->getLinkTitle(),
                    'lft'=>'1',
                    'rgt'=>'2',
                    'datum_start'=>new Zend_Db_Expr('CURDATE()'),
                );
                $pagina = new Model_EigPagina(null,$mapper);
                $pagina->isValid($paginaValues);
            }

        }
    }

    /**
     * De volledige url naar dit domein. Staat niet in de db
     * @return string
     */
    public function getUrlFull() {
        return 'http://'.($this->getWww() ? 'www.' : '').$this->getNaam().'/';//met slash erachter
    }

    public function save() {
        //hier moet acl validation komen, je valideert op
        //2 dingen:
        // update: als $this->_id != null dan $this->getUserId = auth->userid
        // create: heeft user toegang tot type_eig_domein_id
        $freshValueArr = $this->getMapper()->creeerEigDomein($this->valuesArray());
        $this->setValues($freshValueArr);
        return $freshValueArr;
    }

    public function creeerEigDomein() {
        //hier moet acl validation komen, je valideert op
        //2 dingen:
        // update: als $this->_id != null dan $this->getUserId = auth->userid
        // create: heeft user toegang tot type_eig_domein_id
        $values = $this->valuesArray();
        unset($values['id']);
        unset ($values['datum_start']);
        unset ($values['datum_update']);
        $values['randomizer'] = $this->_creeerRandomizer();

        $freshValueArr = $this->getMapper()->creeerEigDomein($values);
        $this->setValues($freshValueArr);
    }

    protected function _creeerRandomizer() {
        $randomizer = rand(0, 999999);
        $validator = new Roens_Validate_Dao_RecordExists(
                array(
                    'table'=>'eig_domein',
                    'field'=>'randomizer',
                    'dao'=>$this->_mapper->getDataAccessObject()
                )
            );
        while ($validator->isValid($randomizer)) {
            $randomizer = rand(0, 999999);
        }
        return $randomizer;
    }

    

    public function valuesArray() {
        $arr = array();
        $arr['id'] = $this->_id;
        $arr['naam'] = $this->_naam;
        $arr['www'] = $this->_www;
        $arr['link_tekst'] = $this->_link_tekst;
        $arr['link_title'] = $this->_link_title;
        $arr['promoinfo'] = $this->_promoinfo;
        $arr['teller'] = $this->_teller;
        $arr['teller_kort'] = $this->_teller_kort;
        $arr['online'] = $this->_online;
        $arr['datum_online'] = $this->_datum_online;
        $arr['title_kort'] = $this->_title_kort;
        $arr['mt_onderwerp_id'] = $this->_mt_onderwerp_id;
        $arr['type_eig_domein_id'] = $this->_type_eig_domein_id;
        $arr['mt_linkpool_id'] = $this->_mt_linkpool_id;
        $arr['mt_hosting_id'] = $this->_mt_hosting_id;
        $arr['mt_server_id'] = $this->_mt_server_id;
        $arr['mt_serveradmin_id'] = $this->_mt_serveradmin_id;
        $arr['mt_scripttype_id'] = $this->_mt_scripttype_id;
        $arr['randomizer'] = $this->_randomizer;
        $arr['datum_start'] = $this->_datum_start;
        $arr['datum_update'] = $this->_datum_aangepast;
        return $arr;
    }
    /**
     * Hier moet ook een acl check komen.
     */
    public function editeer() {
        $values = $this->valuesArray();
        //mogelijk nog meer unsettten als het een user is.
        unset ($values['datum_start']);
        unset ($values['datum_update']);
        unset ($values['randomizer']);

        $freshValueArr = $this->getMapper()->editeerEigDomein($values);
        $this->setValues($freshValueArr);
    }

    public function isValid($values) {
        /*dit valideert het form.*/
        return $this->getForm()->isValid($values);
    }

    public function setFormValues() {
        //$this->getForm()->setDefaults($this->_mapper->find($this->_id)->valuesArray());
    }
    
}