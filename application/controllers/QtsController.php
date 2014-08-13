<?php

class QtsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->db = Zend_Registry::get('db');
        $username = Zend_Registry::get('username');
        $acl = Zend_Registry::get('acl');
        if(FALSE == $acl->isAllowed($username, 'QTS')){
        	exit();
        }
    }
    public function indexAction()
    {
        // Find
        #also could list businesses of type ie unknown license.
    }
    public function addAction()
    {
    	$form = new QTS_Form();
        $form->setAction("/admin/tourism/QTS/add-complete");
        $this->view->form = $form;
    }
    
	public function addCompleteAction()
    {
    	$form = new QTS_Form();
    	$this->render('editComplete');
    	if(isset($_POST['save'])){
    		//if($form->isValid($_POST)){
	    		$var = $form->getValidValues($_POST);
	    		/*
		    	if ($form->pdf->isUploaded()) {
				    $form->pdf->receive();  
		    	}
		    	*/
			    
    			if ($form->pdf->isUploaded()) {
	    			$newfile = $form->pdf->getFileInfo();
	    			$file = $newfile['pdf']['tmp_name'];
	    			$originalFilename = pathinfo($form->pdf->getFileName());
	    			$newFilename = 'file-' . uniqid() . '.' . $originalFilename['extension'];
	    			$form->pdf->addFilter('Rename', $newFilename);
	    			if($form->pdf->receive()){
	    				//Zend_Debug::dump($form->pdf->getFileInfo());
	    			}
	    			$var['pdf'] = $newFilename;
    				$var_pdf['pdf_file'] = $newFilename;
	    			
	    		}
    			try {
			    	$this->db->insert('qts',$var);
			    }catch(Exception $e){
			        $this->view->error = $e;
			    }
			    if($form->pdf->isUploaded()){
			    	$date = new Zend_Date();
    				$var_pdf['uploaded'] = $date->get('YYYY-MM-dd HH:mm:ss');
			    	$var_pdf['accom_id'] = $this->db->lastInsertId('qts','ID');
			    	try {
				   		$this->db->insert('qts_pdf',$var_pdf);
				    }catch(Exception $e){
				        $this->view->error = $e;
				    }
			    }
			    $this->view->message = 'Success!';
    		//}
    	}
    }
    
    public function resultsAction()
    {
    	$vars = $this->_request->getParams();
        $select = $this->db->select()
				       ->from(array('qts'),array('*'))
				       ->where("business_name like '%".$vars['q']."%' OR manager_name like '%".$vars['q']."%'");	        	
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
    }
	public function findAction()
    {
    	$form = new QTS_Search();
        $form->setAction("/admin/tourism/QTS/results");
        $this->view->form = $form;
    }
    public function listallAction()
    {
        $select = $this->db->select()
	        	->from(array('c'=>'qts'),
	        		array('*'))
	        		->order('business_name');
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
        //$this->render('results');
        
    }
    public function editAction()
    {
    	$vars = $this->_request->getParams();
    	if(isset($vars['qid'])){
    		
	        $select = $this->db->select()
	        	->from('qts',
	        		array('*'))
	        	->where('id = '.$vars['qid']);
	        try {
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        	
        		$this->view->error = $e;
        	} 
	       	$result = $stmt->fetchAll();
	        $vals = $result[0];
	        $form = new QTS_Form();  
	        $form->populate($vals);
	        $form->setAction("/admin/tourism/QTS/edit-complete?id=".$vars['qid']);
        	$this->view->form = $form; 
        	//$this->render('add');
        	
        	# get the pdfs 
        	# also the deletion of file is by server admin
        	# first by searching *.09.pdf
        	# second by delete logs for files older than 3 years.
        	
        	$select = $this->db->select()
	        	->from('qts_pdf',
	        		array("concat('http://www.gov.pe.ca/tourism/qts/pdf/',pdf_file) as href","concat('/mnt/qts/',pdf_file) as file","uploaded as name"))
	        	->where('accom_id = '.$vars['qid'])
	        	->order('name asc');
	        try {
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        	
        		$this->view->error = $e;
        	} 
        	
	        $this->view->listlinks = $stmt->fetchAll();
	        
    	}
    }
    public function editCompleteAction()
    {
    	$form = new QTS_Form(); 
    	$vars = $this->_request->getParams();
    	if(isset($_POST['business_name'])){
    		$var = $form->getValidValues($_POST);
    		if ($form->pdf->isUploaded()) {
    			$newfile = $form->pdf->getFileInfo();
    			$file = $newfile['pdf']['tmp_name'];
    			$originalFilename = pathinfo($form->pdf->getFileName());
    			
    			$newFilename = 'file-' . uniqid() . '.' . $originalFilename['extension'];
    			$form->pdf->addFilter('Rename', $newFilename);
    			if($form->pdf->receive()){
    				//Zend_Debug::dump($form->pdf->getFileInfo());
    			}
    			$var['pdf'] = $newFilename;
    			
    			$date = new Zend_Date();
    			$var_pdf['pdf_file'] = $newFilename;
    			$var_pdf['accom_id'] = $vars['id'];
    			$var_pdf['uploaded'] = $date->get('YYYY-MM-dd HH:mm:ss');
	    		try {
	    			$this->db->insert('qts_pdf',$var_pdf);
	    		}catch(Exception $e){
	    			$this->view->message = $e;
	    		}
    		}
    		try {
    			$this->db->update('qts',$var,'id = '.$vars['id']);
    		}catch(Exception $e){
    			$this->view->error = $e;
    		}    	
    		$this->view->message = 'Success!';	
    	}
    	
    }
}









