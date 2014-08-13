<?php

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->db = Zend_Registry::get('db');
        $username = Zend_Registry::get('username');
        $acl = Zend_Registry::get('acl');
        if(FALSE == $acl->isAllowed($username, 'Coupons')){
        	exit();
        }
        
    }

    public function indexAction()
    {
        $db = Zend_Registry::get('db');
        $sql = 'select distinct(username) from permissions';
        $result = $db->fetchAssoc($sql);
		foreach($result as $user){
        	$users[] = $user['username'];
        }
        $this->view->users = $users;
    }

    public function addAction()
    {
    	$form = new Zend_Form;
        $form->setMethod('post');
        $form->setAction("/admin/tourism/users/update");
        $form->addElement('text', 'username', array(
        	'label'=>'Username')
        	);
        $apps = $this->applications();
        //Zend_Debug::dump($apps);
        $form->addElement('multiCheckbox','applications[]',array(
		        'multiOptions' => $apps
		        ));
        $form->addElement('submit', 'save', array('value'=>'Save'));
        $this->view->form = $form;
        $this->render('edit');
    }

    public function editAction()
    {
    	$vars = $this->_request->getParams();
    	$sql = "select aid from permissions where username = '".$vars['u']."' and permission = 1";
        $result = $this->db->fetchAssoc($sql);
		foreach($result as $app){
        	$roles[] = $app['aid'];
        }
    	$form = new Zend_Form;
        $form->setMethod('post');
        $form->setAction("/admin/tourism/users/update");
        $form->addElement('text', 'username', array(
        	'label'=>'Username')
        	);
        $form->username->setValue($vars['u']);
        $apps = $this->applications();
        //Zend_Debug::dump($apps);
        $form->addElement('multiCheckbox','applications[]',array(
		        'value'=> $roles,'multiOptions' => $apps
		        ));
        $form->addElement('submit', 'save', array('value'=>'Save'));
        $this->view->form = $form;
    }

    public function updateAction()
    {
    	$vars = $this->_request->getParams();
    	//Zend_Debug::dump($vars);
    	if(isset($vars['save'])){
    		$sql = "update permissions set permission = 0 where username = '".$vars['username']."'";
        	$result = $this->db->query($sql);
    		foreach($vars['applications'] as $role){
    			$sql = "insert permissions (username,aid,permission) values ('".$vars['username']."',".$role.",1)";
        		$result = $this->db->query($sql);
    		}
    		$sql = "delete from permissions where username = '".$vars['username']."' and permission = 0";
        	$result = $this->db->query($sql);
    	}
    	
    	$this->view->message = "Permissions Updated";
    	$this->render('message');
    }

    public function applications()
    {
        $sql = 'select * from applications';
        $result = $this->db->fetchAssoc($sql);
		foreach($result as $app){
        	$apps[$app['aid']] = $app['aname'];
        }
        return $apps;
    }


}





