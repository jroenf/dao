<?php
class Roen_Application_Resource_Routepages
extends Zend_Application_Resource_ResourceAbstract {

    protected $_router;

    public function init() {
        return $this->_getRouter();
    }

    protected function _getRouter() {
        if (null === $this->_router) {

            $options = $this->getOptions();
            if (!isset($options['routes'])) {
                $options['routes'] = array();
            }
            
            $bootstrap = $this->getBootstrap();
            $bootstrap->bootstrap('FrontController');
            $this->_router = $bootstrap->getContainer()->frontcontroller->getRouter();


            if (isset($options['chainNameSeparator'])) {
                $this->_router->setChainNameSeparator($options['chainNameSeparator']);
            }

            if (isset($options['useRequestParametersAsGlobal'])) {
                $this->_router->useRequestParametersAsGlobal($options['useRequestParametersAsGlobal']);
            }

            $this->_router->addConfig(new Zend_Config($options['routes']));

            //Pagina's ophalen, met cache of zonder cache;
            $paginaRoutes = array();
            if (isset($options['usecache']) && $options['usecache']) {
                $cacheManager = $bootstrap->bootstrap('CacheManager')
                        ->getResource('CacheManager');
                $cache = $cacheManager->getCache($options['cachenaam']);
                if (!($cache->test('routes'))) {
                    $paginaRoutes = $this->_getPaginaRoutes();
                    $cache->save($paginaRoutes);
                } else {
                    $paginaRoutes = $cache->load('routes');
                }
            } else { //helemaal geen caching
                $paginaRoutes = $this->_getPaginaRoutes();
            }
            foreach ($paginaRoutes as $routename => $paginaRouter) {
                $this->_router->addRoute($routename,$paginaRouter);
            }
        }
        return $this->_router;
    }

    protected function _getPaginaRoutes() {
        $dao = $this->getBootstrap()
                ->bootstrap('dao')
                ->getResource('dao');
        $options = $this->getOptions();
        $domeinId = $options['domeinid'];
        $paginas = $dao->paginas4routes($domeinId);
        $paginaRouters = array();
        foreach($paginas as $pagina) {
            $paginaRouter = new Zend_Controller_Router_Route(
                    $pagina['url-absolute'],//.'/*',
                    array(
                        'module'=>'default',
                        'controller'=>'pagina',
                        'action'=>$pagina['type-eig-pagina'],
                        'domeinid'=>$domeinId,
                        'paginaid'=>$pagina['eig-pagina-id'],
                    ));
            $routename = 'id'.$pagina['eig-pagina-id'];
            $paginaRouters[$routename] = $paginaRouter;
        }
        return $paginaRouters;
    }
}