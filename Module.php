<?php
namespace ValuCli;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module
    implements Feature\AutoloaderProviderInterface, 
               Feature\ConfigProviderInterface,
               Feature\ConsoleBannerProviderInterface
{
    
    /**
     * getAutoloaderConfig() defined by AutoloaderProvider interface.
     * 
     * @see AutoloaderProvider::getAutoloaderConfig()
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }
    
    /**
     * getConfig implementation for ConfigListener
     * 
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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