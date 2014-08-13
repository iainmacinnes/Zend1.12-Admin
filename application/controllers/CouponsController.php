<?php

class CouponsController extends Zend_Controller_Action
{

    public $db = null;

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
        	#SELECT * FROM `coupons` c 
    	#left join (select orig_couponid as translated from coupons) o 
    	#on c.couponid = o.translated
       	$select = $this->db->select()
	        	->from(array('c'=>'coupons'),
	        		array('*'))
	        	->joinLeft(array('o'=>'coupons'),
	        		'o.orig_couponid = c.couponid',
	        		array('translated'=>'orig_couponid','tran_couponid'=>'couponid'))
	        	->where("c.lang = 'E'")
	        	->limit(5);
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
	    
	    //var_dump($result);
	    
    
    }

    public function translateAction()
    {
        $vars = $this->_request->getParams();
    	if(isset($vars['couponid'])){
    		//var_dump($vars);
	        $select = $this->db->select()
	        	->from('coupons',
	        		array('*'))
	        	->where('couponid = '.$vars['couponid']);
	        try {
        		//$sql = $select->__toString();
				//echo "$sql\n";
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        		$this->view->error = $e;
        	} 
	       	$result = $stmt->fetchAll();
	        $vals = $result[0];
	        $form = new Coupon_Form();  
	        $form->setAction("/admin/tourism/coupons/translate");
	        $form->populate($vals);
	        $form->lang->setValue('F');
	        $form->orig_couponid->setValue($vars['couponid']);
        	$this->view->form = $form; 
        	$this->view->title = "Translate Coupon";
        	$this->render('add');        
        	//$this->view->logo = "<img src=\"/coupons/logos/".$vals['logo']."\" />";
        	
    	}
        	
	    	if(isset($_POST['save'])){
	    		//Zend_Debug::dump($_POST);
	    		$form = new Coupon_Form();  
	    		//$form->location->setValue($_POST['location']);
	    		//if($form->isValid($_POST)){
		    		$var = $form->getValidValues($_POST);
		    		$var['keywords'] = Zend_Json::encode($var['keywords']);
	    			$var['akeywords'] = Zend_Json::encode($var['akeywords']);
		    		/*
			    	if ($form->logo->isUploaded()) {
					    $form->logo->receive(); 
			    	}
			    	*/
				    try {	
				    	$this->db->insert('coupons',$var);	
				    }catch(Exception $e){
				        $this->view->error = $e;
				    } 
				    $this->view->message = "Translation Made";    
			    	return $this->_forward('message');
	    		//}
	    	}

    }

    public function addAction()
    {
        $form = new Coupon_Form();
        $form->setAction("/admin/tourism/coupons/add");
        $this->view->form = $form;
        $this->view->title = 'Add New Coupon';
    	if(isset($_POST['save'])){
    		//$form->location->setValue($_POST['location']);
    		//if($form->isValid($_POST)){
	    		$var = $form->getValidValues($_POST);
	    		$var['keywords'] = Zend_Json::encode($var['keywords']);
	    		$var['akeywords'] = Zend_Json::encode($var['akeywords']);
	    	    /*  
		    	if ($form->logo->isUploaded()) {
				    $form->logo->receive();  
		    	}
			    */
	    		//Zend_Debug::dump($var);
			    try {
			    	$this->db->insert('coupons',$var);
			    }catch(Exception $e){
			        $this->view->error = $e;
			    }
			    $this->view->message = "<script type=\"text/javascript\">$('#mainform').hide('slow');</script>";
			    $this->view->message .= "Coupon Added";    
			    return $this->_forward('message');
    		//}
    	}
    }

    public function editAction()
    {
    	$this->view->title = 'Edit Coupon';
    	$vars = $this->_request->getParams();
    	if(isset($vars['couponid'])){
    		$form = new Coupon_Form();
	    	if(isset($_POST['save'])){
	    		//Zend_Debug::dump($_POST);
			   	//if($form->isValid($_POST)){
				$var = $form->getValidValues($_POST); 
				$var['keywords'] = Zend_Json::encode($var['keywords']);
	    		$var['akeywords'] = Zend_Json::encode($var['akeywords']);
				//var_dump($var);
				/*  
				if ($form->logo->isUploaded()) {
					$form->logo->receive();  
			    } 
			    */		
				try {
					$this->db->update('coupons',$var,'couponid = '.$vars['couponid']);
				}catch(Exception $e){
				    $this->view->error = $e;
				}
				//return $this->_forward('edit-coupon');
				$this->view->message = "Coupon Updated";    
		    	return $this->_forward('message');
	    		//}
	    	}
    		//var_dump($vars);
	        $select = $this->db->select()
	        	->from('coupons',
	        		array('*'))
	        	->where('couponid = '.$vars['couponid']);
	        try {
        		//$sql = $select->__toString();
				//echo "$sql\n";
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        		$this->view->error = $e;
        	} 
	       	$result = $stmt->fetchAll();
	        $vals = $result[0];
	        //var_dump($vals);
	        $vals['keywords'] = Zend_Json::decode($vals['keywords']);
	        $vals['akeywords'] = Zend_Json::decode($vals['akeywords']);
	        //Zend_Debug::dump($vals);
	        $form->populate($vals);
	        $this->view->script =
	        '$(function() {
				$( "#startdate" ).datepicker( "setDate", "'.$vals["startdate"].'" );
				$( "#enddate" ).datepicker( "setDate", "'.$vals["enddate"].'" );
			});';
	        //$form->location->setValue($vals['location']);
	        $form->setAction("/admin/tourism/coupons/edit?couponid=".$vars['couponid']);
        	$this->view->form = $form; 
        	//$this->view->logo = "<img src=\"/admin/tourism/coupons/logos/".$vals['logo']."\" />";
        	$this->render('add'); 
        	
    	}
    	
    }

    public function removeAction()
    {
 		$vars = $this->_request->getParams();
    	if(isset($vars['couponid'])){
			$this->view->couponid = $vars['couponid'];
    	}
    	
    }

    public function deleteAction()
    {
    	$vars = $this->_request->getParams();
    	if(isset($vars['id'])){
    		$db = Zend_Registry::get('db');
	        try {
	        	$result = $db->query('delete from coupons 
	        where couponid = ?
	        or orig_couponid = ?',array($vars['id'],$vars['id']));
	        }catch(Zend_Db_Adapter_Exception $e){
	        	$this->view->error = $e;
	        }	
    		$this->view->message = "Coupon Removed";    
		    return $this->_forward('message');
    	}
    }

    public function listAction()
    {
        $select = $this->db->select()
	        	->from(array('c'=>'coupons'),
	        		array('*'))
	        	->joinLeft(array('o'=>'coupons'),
	        		'o.orig_couponid = c.couponid',
	        		array('translated'=>'orig_couponid','tran_couponid'=>'couponid'))
	        	->where("c.lang = 'E'");
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
        $this->render('index');
    }
    
    public function tagAction()
    {
    	$form = new Coupon_Tag();
        $form->setAction("/admin/tourism/coupons/tag");
        $this->view->form = $form;
        $this->view->title = "Create New Keyword";
               
    	if(isset($_POST['save'])){
    		$var = $form->getValidValues($_POST);
    		try {
    			$this->db->insert('coupon_tags',$var);
    		}catch(Exception $e){
    			$this->view->error = $e;
    		}
    		$this->view->message = "Tag Added";
    		return $this->_forward('message');
    	}
    }
    
	public function hiddentagAction()
    {
    	$form = new Coupon_Tag();
        $form->setAction("/admin/tourism/coupons/hiddentag");
        $form->addElement('hidden', 'authority', array(
        	'value'=>'1')
        );
        $this->view->title = "Special Keyword";
        $this->view->form = $form;
        $this->render('tag');        
    	if(isset($_POST['save'])){
    		$var = $form->getValidValues($_POST);
    		try {
    			$this->db->insert('coupon_tags',$var);
    		}catch(Exception $e){
    			$this->view->error = $e;
    		}
    		$this->view->message = "Tag Added";
    		return $this->_forward('message');
    	}
    }
    
    
    public function messageAction()
    {
    	
    }

}











