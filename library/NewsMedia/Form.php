<?php

class NewsMedia_Form extends Zend_Form  
{

	public function init()
	{

		//$this = new Zend_Form;
        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('id', 'newsMedia');
		
        $this->addElement('text', 'ntitle', array(
        	'label'=>'Title')
        );
        $this->ntitle->setRequired(true);
        
        $this->addElement('text', 'url', array(
        	'label'=>'Full URL')
        );
        
        $this->addElement('text', 'youtube', array(
        	'label'=>'Full Youtube link')
        );
        
        $this->addElement('textarea', 'ndescription', array(
        	'label'=>'Description')
        );
        $this->ndescription->setAttribs(array('rows'=>'8','cols'=>'80')); 
        
        $this->addElement('file', 'image', array(
        	'label'=>'Image'
        ));
        
        $this->image->setDestination('/www/htdocs/visitorsguide/images/newsMedia');
        $this->image->addValidator('Extension',false,'jpg,png,gif');
        
        //$originalFilename = pathinfo($this->image->getFileName());
	    //$newFilename = 'file-' . uniqid() . '.' . $originalFilename['extension'];
	    //$this->image->addFilter('Rename', $newFilename);
        
        //$this->image->addFilter('Rename',array('target'=>time()));
        
        $this->addElement('select', 'lang', array(
        	'label'=>'Language')
        );  
        $this->lang->addMultiOptions(array(
        	'en'=>'English',
        	'fr'=>'French')
        );
        
        $this->addElement('submit', 'save', array('value'=>'Save'));
		
		
	}
	

	
}