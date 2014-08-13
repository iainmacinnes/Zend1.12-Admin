<?php

class SnowController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->db = Zend_Registry::get('db');
        $username = Zend_Registry::get('username');
        $acl = Zend_Registry::get('acl');
        if(FALSE == $acl->isAllowed($username, 'Snow')){
        	exit();
        }
        $front = zend_controller_front::getInstance();
    	$front->getResponse()->setHeader('Cache-Control', 'no-cache');
    	$front->getResponse()->setHeader('Pragma', 'no-cache');
    }

    public function indexAction()
    {
        $snoweditor = new Application_Form_Snoweditor();
        $snoweditor->conditions("GENERAL");
        $this->view->form = $snoweditor;
        
    }

    public function editAction()
    {
    	$vars = $this->_request->getParams();
    	//Zend_Debug::dump($vars);
    	foreach($vars as $i => $v){
    		if(stristr($i,'FormElement')){
    			$qid = preg_replace('[\D]', '', $i);
    			$select = "update snow_values set val = '".$v."' where qid = ".$qid;
    			$this->db->query($select);
    		}
    	}
    }
    
	public function nordicAction()
    {
        $snoweditor = new Application_Form_Snoweditor();
        $snoweditor->conditions("NORDIC");
        $this->view->form = $snoweditor;
        $this->render('index');
        
    }
    
	public function alpineAction()
    {
        $snoweditor = new Application_Form_Snoweditor();
        $snoweditor->conditions("ALPINE");
        $this->view->form = $snoweditor;
        $this->render('index');
        
    }
    
	public function nordictrailsAction()
    {
        $snoweditor = new Application_Form_Snoweditor();
        $snoweditor->conditions("NORDICTRAILS");
        $this->view->form = $snoweditor;
        $this->render('index');
        
    }

}

