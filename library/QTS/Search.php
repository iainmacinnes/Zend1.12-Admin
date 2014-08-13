<?php

class QTS_Search extends Zend_Form  
{

	public function init()
	{

        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
        $this->addElement('text', 'q', array(
        	'label'=>'Keyword Search')
        );
        
        
        $this->addElement('submit', 'search', array('value'=>'Search'));
        
	}
	
}