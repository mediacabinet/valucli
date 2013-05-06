<?php
namespace ValuCli;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

class Module
    implements Feature\AutoloaderProviderInterface, 
               Feature\ConfigProviderInterface,
               Feature\ConsoleBannerProviderInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__
                ),
            ),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
    public function getConsoleBanner(Console $console)
    {
        return
            "==------------------------------------------------------==\n" .
            "                        Valu Console                      \n" .
            "==------------------------------------------------------==\n"
        ;
    }
}