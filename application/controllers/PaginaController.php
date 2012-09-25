<?php
/**
 * The PageController recieves setup from bootstrap, 
 * creates page model, and renders the page
 * 
 * ToDo: convert all Dutch names to English
 *  
 */
class PageController extends Zend_Controller_Action
{
    /**
     * Page model, holds references to content
     * 
     * @var Model_EigPagina
     */
    protected $_pagina;
    /**
     * Domain model, for domain specific settings and related pages
     * 
     * @var Model_EigDomein
     */
    protected $_domein;
    /**
     * The model mapper is used to get data from the data-acces-object and 
     * map it to a model.
     * 
     * @var Model_Mapper_Default
     */
    protected $_mapper;

    /**
     * Setup of the paginaController,  
     * Gets parameters from request, resources 
     * from the bootstrap. Initialises page caching if needed. And sets up view.
     */
    public function init()
    {
        $bootstrap = $this->getFrontController()->getParam('bootstrap');
        $dao = $bootstrap->getResource('dao');
        $this->_mapper = new Model_Mapper_Default(array('dao'=>$dao));
        $this->_pagina  = $this->_mapper->findEigPagina((int)$this->getRequest()->getParam('paginaid'));
        $this->_domein = $this->_pagina->getReferenceEigDomein();
        
        $this->_helper->cache(
                array(
                    'homepage1',
                    'subpage1',
                    'moviepage1',
                    'simpledialhome',
                    'simpledialsub',
                    'customhome',
                    'volbreedfilm',
                    'volbreedhome',
                    ),
                array(
                    $this->_pagina->getReferenceTypeEigPagina()->getNaam(),
                    $this->_pagina->getReferenceEigDomein()->getReferenceTypeEigDomein()->getNaam(),
                    'all',
                    'pagina'));
        
        // Abstract this to a viewsetup plugin
        $this->view->mapper = $this->_mapper;
        $this->view->pagina = $this->_pagina;
        $this->view->domein = $this->_domein;
        
        $this->view->doctype('HTML5');
        $this->view->headTitle($this->_domein->getTitleKort());
        $this->view->headTitle()->setSeparator(' | ');
        $this->view->headTitle()->prepend(ucfirst($this->_pagina->getTitle()));
    }
    
    /**
     * When data-acces-object is a xml-rpc server you can test the server with this client.
     */
    public function xmlservertestAction() {
        $client = new Zend_XmlRpc_Client('http://www.example.com/xmlrpc');
        $this->view->serverResponse = 
                $client->call('dao.test',array('Message','The test succeeded'));
    }
    
    /**
     * If page-type is homepage this action is 
     */
    public function homepage1Action() {
        $desc = $this->_pagina->getDescription();
        $description = $this->view->bbCode()->remove($desc);
        $this->view->headMeta($description,'description');
    
        // View scripts uitvoeren.
        $this->renderScript('pagina/homepage1/_kop.phtml','kop');
        $this->renderScript('pagina/homepage1/_main-menu.phtml','kolom');
        $this->renderScript('pagina/homepage1.phtml','default');
        $this->renderScript('pagina/homepage1/_voet.phtml','voet');

        //Units opvullen naar de bijbehorende namedsegments
        $namedsegments = $this->_pagina->getDependentEigNamedsegment();
        foreach ($namedsegments as $namedsegment) {
            $namedResponseSegment = $namedsegment->getNaam();
            $units = $namedsegment->getDependentEigUnit();
            foreach ($units as $unit) {
                $this->view->unit = $unit;
                $unitType = $unit->getReferenceTypeEigUnit()->getNaam();
                $this->renderScript('_eig-unit/view/type/_'.$unitType.'.phtml',$namedResponseSegment);
            }
        }
    }
}



