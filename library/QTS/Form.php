<?php

class QTS_Form extends Zend_Form  
{

	public function init()
	{

        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
        $this->addElement('text', 'business_name', array(
        	'label'=>'Business Name')
        );
        $this->business_name->setRequired(true);
        
		$this->addElement('select', 'accomtype', array(
        	'label'=>'Type')
        );  
        $this->accomtype->addMultiOptions(array(
        	'accom'=>'accom',
        	'camp'=>'camp',
        	'trailer'=>'trailer')
        );
        
        $this->addElement('select', 'licensed', array(
        	'label'=>'Licensed')
        );  
        $this->licensed->addMultiOptions(array(
        	''=>'',        	
        	'YES'=>'YES',
        	'NOP'=>'NOP',
        	'NO'=>'NO',
        	'INC'=>'INC',
        	'NC'=>'NC')
        );
        /*
         * 'APPL'=>'APPL',
         * NOP		No Longer Operating
         * NO		Revoked
         * YES		Licensed
         * NC		Non Compliant
         */
        
        $this->addElement('text', 'first_name', array(
        	'label'=>'First Name')
        );
        
        $this->addElement('text', 'last_name', array(
        	'label'=>'Last Name')
        );
        
        $this->addElement('text', 'manager_name', array(
        	'label'=>'Manager Name')
        );
        
        $this->addElement('text', 'phone_winter', array(
        	'label'=>'Phone')
        );
        
        $this->addElement('text', 'email', array(
        	'label'=>'Email')
        );
        
        $this->addElement('text', 'community', array(
        	'label'=>'Community')
        );
        
        
        $this->addElement('text', 'civic_address', array(
        	'label'=>'Civic Address')
        );
        
        $this->addElement('file', 'pdf', array(
        	'label'=>'PDF File'
        ));
        
        //$this->pdf->setDestination('/www/framework/zend/tourism/public/pdf/');
        $this->pdf->setDestination('/mnt/qts/');
        $this->pdf->addValidator('Extension',false,'pdf');
        
        /*
        $this->addElement('file', 'image', array(
        	'label'=>'Image'
        ));        
        $this->image->setDestination('/www/htdocs/visitorsguide/images/newsMedia');
        $this->image->addValidator('Extension',false,'jpg,png,gif');
        */
        
        $this->addElement('submit', 'save', array('value'=>'Save'));
        
	}
	
}