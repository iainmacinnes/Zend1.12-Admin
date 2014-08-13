<?php

class Coupon_Tag extends Zend_Form  
{

	public function init()
	{

		//$this = new Zend_Form;
        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		
		
		$this->addElement('text', 'tag', array(
        	'label'=>'Tag')
        );
        
        $this->addElement('submit', 'save', array('value'=>'Save'));
		
	}

	
}