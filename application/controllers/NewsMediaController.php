<?php

class NewsMediaController extends Zend_Controller_Action
{

    public $db = null;

    public function init()
    {
        $this->db = Zend_Registry::get('db');


    
    }

    public function indexAction()
    {
        //return $this->_forward('add');
    }

    public function addAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$form = new NewsMedia_Form();
        
    	$form->setAction("/admin/tourism/news-media/add-complete");
    	
        $this->view->form = $form;	
    	
    }

    public function addCompleteAction()
    {
    	
    	$this->_helper->layout()->disableLayout();
    	$form = new NewsMedia_Form();

    	if(isset($_POST['ntitle'])){
    		
    		//if($form->isValid($_POST)){
    			
	    		$var = $form->getValidValues($_POST);
	    	    //$var = $form->getValues();
	    	    //Zend_Debug::dump($var, 'Form Data:');
	    	    
		    	if ($form->image->isUploaded()) {
		    		
				    $newfile = $form->image->getFileInfo();
				    //Zend_Debug::dump($newfile, 'File Data:');
				    /*
				    if(! $form->image->receive()){
					    	die();
				    };
				    */
				    $file = $newfile['image']['tmp_name'];
				    $this->resizeImage($file);
				    
				    $originalFilename = pathinfo($form->image->getFileName());
				    $newFilename = 'file-' . uniqid() . '.' . $originalFilename['extension'];
				    $form->image->addFilter('Rename', $newFilename);
				    
				    if(! $form->image->receive()){
					    	die();
					    	
				    };
				    
				    $var['image'] = $newFilename;
				    //Zend_Debug::dump($var, 'Form Data:');
				    
		    	}
		    	
			    try {
			    	
			    	$this->db->insert('newsMedia',$var);
			    	
			    }catch(Exception $e){
			    	
			        $this->view->error = $e;
			        
			    }
			    
			     //$uploadedData = $form->getValues();
			     //Zend_Debug::dump($uploadedData, 'Form Data:');
				
    		//}else{
    			
    			//Zend_Debug::dump($_POST, 'Form Data:');
    			//$this->view->error = "Form Invalid";
    			
    		//}
    		
    	}else{
    		
    		$this->view->error = "Please use form";
    		
    	}
    	
    }

    public function editAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$vars = $this->_request->getParams();
    	if(isset($vars['nid'])){
    		//var_dump($vars);
	      
	        $select = $this->db->select()
	        	->from('newsMedia',
	        		array('*'))
	        	->where('nid = '.$vars['nid']);

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
	        $form = new NewsMedia_Form();  
	        $form->populate($vals);
	        //$form->location->setValue($vals['location']);
	        $form->setAction("/admin/tourism/news-media/edit-complete?nid=".$vars['nid']);
        	$this->view->form = $form; 
        	$this->view->image = "<img src=\"/admin/tourism/images/newsMedia/".$vals['image']."\" />";
        	
    	}

    	
    }

    public function editCompleteAction()
    {
    		$this->_helper->layout()->disableLayout();
    		$form = new NewsMedia_Form(); 
    		$vars = $this->_request->getParams();
    		if(isset($_POST['ntitle'])){
    			
    			$var = $form->getValidValues($_POST);
		    		
		    	//if($form->isValid($_POST)){
			    //$var = $form->getValues();
			    //Zend_Debug::dump($vars, 'Form Values:');

			    if ($form->image->isUploaded()) {
			    	/*
			    	 * $newfile = $form->image->getFileInfo();
			    	 * //Zend_Debug::dump($newfile, 'File Data:');
			    	 * $form->image->receive();
			    	 * $file = $newfile['image']['tmp_name'];
			    	 * $this->resizeImage($file);
			    	 * */
			    	
			    	$newfile = $form->image->getFileInfo();
				    //Zend_Debug::dump($newfile, 'File Data:');
				    /*
				    if(! $form->image->receive()){
					    	die();
				    };
				    */
				    $file = $newfile['image']['tmp_name'];
				    $this->resizeImage($file);
				    
				    $originalFilename = pathinfo($form->image->getFileName());
				    $newFilename = 'file-' . uniqid() . '.' . $originalFilename['extension'];
				    $form->image->addFilter('Rename', $newFilename);
				    
				    if(! $form->image->receive()){
					    	die();
					    	
				    };
				    
				    $var['image'] = $newFilename;
				    //Zend_Debug::dump($vars, 'Form Data:');
			    }
			    
			    try {
			    	//Zend_Debug::dump($var, 'Form Values:');
			    	//$form->populate($var);
			    	$this->db->update('newsMedia',$var,'nid = '.$vars['nid']);
			    	
			    }catch(Exception $e){

			    	$this->view->error = $e;
			    	
			    }
					 
					//return $this->_forward('edit-coupon');

		    	//}
    		}
    		
    }

    public function removeAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$vars = $this->_request->getParams();
    	if(isset($vars['nid'])){
        
	        try {
	        	$this->db->update('newsMedia',array("display"=>0),'nid = '.$vars['nid']);
	        	//$result = $db->query('delete from newsMedia where nid = ?',array($vars['id'],$vars['id']));
	        	
	        }catch(Zend_Db_Adapter_Exception $e){
	        	
	        	$this->view->error = $e;
	        	
	        }	
    		
    	}
    }
    
    function resizeImage($file){
    	require_once 'PhpThumbFactory/PhpThumbFactory.php';

		$thumb = PhpThumbFactory::create($file);
		$thumb->adaptiveResize(150, 150);
		$thumb->save($file);

    }
    

    public function listAction()
    {
    	$this->_helper->layout()->disableLayout();
        $select = $this->db->select()
	        	->from(array('c'=>'newsMedia'),
	        		array('*'))
	        	->where('display = 1')
	        	->order('nid desc')
	        	->limit(5);
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
    }

	public function listallAction()
    {
        $this->_helper->layout()->disableLayout();
        $select = $this->db->select()
	        	->from(array('c'=>'newsMedia'),
	        		array('*'))
	        	->where('display = 1')
	        	->order('nid desc');
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
        $this->render('list');
    }

}













