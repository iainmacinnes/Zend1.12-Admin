<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDatabase(){
        // get config from config/application.ini
        $config = $this->getOptions();
        
        $db = Zend_Db::factory($config['resources']['db']['adapter'],
         					   $config['resources']['db']['params']);
        
        //set default adapter
        Zend_Db_Table::setDefaultAdapter($db);

        //save Db in registry for later use
        Zend_Registry::set("db", $db);
        
    }
	
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        
    }

    
    public function _initAutoloading()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('Coupon_');
        $loader->registerNamespace('NewsMedia_');
        $loader->registerNamespace('QTS_');
    }

    protected function _initACL()
    {
    	
    	$acl = new Zend_Acl();
     
    	$acl->addRole(new Zend_Acl_Role($_SERVER["REMOTE_USER"]));
     
	    //$parents = array('guest', 'member', 'admin');
	    //$acl->addRole(new Zend_Acl_Role('someUser'), $parents);
	     
	    //$acl->add(new Zend_Acl_Resource('someResource'));
	    #$acl->addResource($resource,$parent);
	     
	    //$acl->deny('guest', 'someResource');
	    #$acl->allow($_SERVER["REMOTE_USER"], 'someResource');
	    
    	Zend_Registry::set('username', $_SERVER["REMOTE_USER"]);
	    
	    
	    $db = Zend_Registry::get('db');
	    
	    /*
	     * Add Resources
	     */
	    $select = $db->select()
	        	->from(array('applications'),
	        		array('resource'=>'aname'));
	   	try {
	   		$stmt = $db->query($select);
	   		$result = $stmt->fetchAll();
	    	//$this->view->result = $result;
	    	//Zend_Debug::dump($result);
	    	
	    	foreach($result as $resource){
	    		$acl->addResource($resource['resource']);
	    	}
	    	
        }catch(Zend_Db_Adapter_Exception $e){
        	//$this->view->error = $e;
        	//Zend_Debug::dump($e);
        } 
        
        /*
         * Add permissions
         * 
         */
	    $select = $db->select()
	        	->from(array('p'=>'permissions'),
	        		array('*'))
	        	->joinLeft(array('a'=>'applications'),
	        		'a.aid = p.aid',
	        		array('resource'=>'aname'))
	        	->where("p.username = '".$_SERVER["REMOTE_USER"]."'")
	        	->where("p.permission = 1");
	   	try {
	   		$stmt = $db->query($select);
	   		$result = $stmt->fetchAll();
	    	//$this->view->result = $result;
	    	//Zend_Debug::dump($result);
	    	
	    	foreach($result as $resource){
	    		$acl->allow($_SERVER["REMOTE_USER"], $resource['resource']);
	    	}
	    	
        }catch(Zend_Db_Adapter_Exception $e){
        	//$this->view->error = $e;
        } 
        
        Zend_Registry::set('acl', $acl);
	     
	    #echo $acl->isAllowed('someUser', 'someResource') ? 'allowed' : 'denied';
    }
    
    public function _initDojo(){
    	// Create new view object if not already instantiated  
		//$view = new Zend_View();
		#$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		#Zend_Dojo::enableView($view);
		//Zend_Dojo::enableView($view);
		//$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		//$viewRenderer->setView($view);	

    }
    
}

