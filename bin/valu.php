#!/usr/bin/env php
<?php
use Zend\Console;
use Zend\Loader\StandardAutoloader;

chdir(dirname(__DIR__).'/../..');

// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();

// Setup autoloading
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

$rules = array(
        'help|h' => 'Show this help (-h is --help only if used alone)',
        'username|u' => 'Username',
        'service|s' => 'Service',
        'operation|o' => 'Operation',
);

try {
    $opts = new Console\Getopt($rules);
    $opts->parse();
} catch (Console\Exception\RuntimeException $e) {
    echo $e->getUsageMessage();
    exit(2);
}

if ($opts->getOption('h')) {
    echo $opts->getUsageMessage();
    exit(0);
}