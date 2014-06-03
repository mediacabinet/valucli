<?php
namespace ValuCli;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module
    implements Feature\ConfigProviderInterface,
               Feature\ConsoleBannerProviderInterface
{
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