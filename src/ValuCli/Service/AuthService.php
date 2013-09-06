<?php
namespace ValuCli\Service;

use ValuSo\Annotation as ValuService;
use ValuSo\Feature;
use Zend\Authentication\Storage\NonPersistent;
use Zend\Mvc\MvcEvent;
use Zend\Console\Request as ConsoleRequest;
use Zend\Console\Prompt;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * Provides console authentication mechanism
 * 
 * @author juhasuni
 *
 */
class AuthService
    implements Feature\ServiceBrokerAwareInterface
{
    use Feature\ServiceBrokerTrait;
    
    /**
     * Authentication storage instance
     * 
     * @var Zend\Authentication\Storage\StorageInterface
     */
    private $storage;
    
    /**
     * Private copy of user's identity
     * 
     * @var array
     */
    private $identity = null;
    
    /**
     * Console instance
     * 
     * @var Console
     */
    private $console;

    /**
     * Authenticate user
     * 
     * @param MvcEvent $event
     * @return NULL|boolean|\Zend\Authentication\Result
     * 
     * @ValuService\Context("native")
     */
	public function authenticate(MvcEvent $event)
	{
	    // Skip if not console request
	    if (!($event->getRequest() instanceof ConsoleRequest)) {
	        return null;
	    }
	    
	    $routeMatch = $event->getRouteMatch();
	    
	    // Use pre-defined identity, if available
	    $identity = $routeMatch->getParam('identity');
	    if ($identity && is_array($identity)) {
	        $this->getStorage()->write($identity);
	        return new Result(Result::SUCCESS, $identity);
	    }
	    
	    // Fetch username and password
	    $username = $routeMatch->getParam('user', $routeMatch->getParam('u'));
	    $password = $routeMatch->getParam('password', $routeMatch->getParam('p'));
	    
	    // Prompt username if not given
	    if (!$username) {
	        $confirm = new Prompt\Line('Enter username:');
	        $username = $confirm->show();
	    }
	    
	    if (!$username) {
	        return false;
	    }
	    
	    // Prompt password if not given
	    if (!$password) {
	        $confirm = new Prompt\Line('Enter password:');
	        $password = $confirm->show();
	    }
	    
	    if (!$password) {
	        return false;
	    }
	    
	    // Resolve identity
	    $identity = $this->resolveIdentity($username, $password);

        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }
        
        $this->getStorage()->write($identity);
        
        return new Result(Result::SUCCESS, $identity);
	}
	
	/**
     * Returns the identity or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        if($this->hasIdentity()){
            return $this->getStorage()->read();
        }
        else{
            return null;
        }
    }
    
    /**
     * Returns true if and only if an identity is available
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }

    /**
     * Clears the identity
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }
	
	/**
	 * Set storage instance
	 * 
	 * @param StorageInterface $storage
	 * 
	 * @ValuService\Exclude
	 */
	public function setStorage(StorageInterface $storage)
	{
	    $this->storage = $storage;
	}
	
	/**
	 * Retrieve storage instance
	 * 
	 * @return \Zend\Authentication\Storage\StorageInterface
	 * 
	 * @ValuService\Exclude
	 */
	public function getStorage()
	{
	    if(!$this->storage){
	        $this->setStorage(new NonPersistent());
	    }
	    
	    return $this->storage;
	}
    
	/**
     * @return \Zend\Console\Adapter\AdapterInterface
     */
    public function getConsole()
    {
        if (!$this->console && $this->getServiceLocator()) {
            $this->setConsole($this->getServiceLocator()->get('console'));
        }
        
        return $this->console;
    }

	/**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Resolve identity by username and password
     * 
     * @param string $username
     * @param string $password
     * @return array
     */
    private function resolveIdentity($username, $password)
    {
        $result = false;
        
        if ($this->getServiceBroker()) {
            $result = $this->getServiceBroker()
                ->service('User')
                ->resolveIdentity($username, $password);
        }
        
        return is_array($result) ? $result : false;
    }
}