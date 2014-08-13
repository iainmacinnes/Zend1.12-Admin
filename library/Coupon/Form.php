<?php

class Coupon_Form extends Zend_Form  
{

	public function init()
	{
	 	$acl = Zend_Registry::get('acl');
        $username = Zend_Registry::get('username');

		//$this = new Zend_Form;
        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$this->addElement('submit', 'save', array('value'=>'Save'));
		
		$this->addElement('text', 'oid', array(
        	'label'=>'Operator ID',
		'onchange'=>'javascript:getOperator();')
        );
        /*
        $this->addElement('text', 'facility', array(
        	'label'=>'Facility',
        	'size'=>'50')
        );
        
        $this->facility->setRequired(true);
        
        $this->addElement('text', 'location', array(
        	'label'=>'Location')
        );
        
        $this->addElement('select', 'rating', array(
        	'label'=>'Star rating')
        );
        */
        $this->addElement('text', 'tagline', array(
        	'label'=>'Tag Line')
        );
        
        
        $this->addElement('text', 'savetag', array(
        	'label'=>'Sell Line')
        );
        $this->addElement('multiCheckbox', 'keywords', array(
        	'label'=>'Tags')
        );
        
        $tags = $this->tags();
        $this->keywords->addMultiOptions(
        	$tags
        );
        
		if(TRUE == $acl->isAllowed($username, 'Advanced Tags')){
        	$this->addElement('multiCheckbox', 'akeywords', array(
        		'label'=>'Advanced Tags')
	        );
	        
	        $tags = $this->tags(1);
	        $this->akeywords->addMultiOptions(
	        	$tags
	        );
        }
        /*
        $this->addElement('select', 'location', array(
        	'label'=>'Location')
        );
        
        $places = $this->places();
        $this->location->addMultiOptions(
        	$places
        );
        */
        $this->addElement('textarea', 'description', array(
        	'label'=>'Coupon Description')
        );
        $this->description->setAttribs(array('rows'=>'8','cols'=>'80'));
        
        $this->addElement('textarea', 'conditions', array(
        	'label'=>'Conditions')
        );
        $this->conditions->setAttribs(array('rows'=>'8','cols'=>'80'));
        /*
        $this->addElement('select', 'type', array(
        	'label'=>'Coupon Type')
        );  
        $this->type->addMultiOptions(array(
        	'-1'=>'Select Type',
        	'1'=>'Restaurant',
        	'2'=>'Golf',
        	'3'=>'Activity',
        	'4'=>'Shopping',
        	'5'=>'Accommodation')
        );
        */
        /*
        $this->addElement('file', 'logo', array(
        	'label'=>'Coupon\'s Logo'
        ));
        
        $this->logo->setDestination('/www/htdocs/visitorsguide/coupons');
        $this->logo->addValidator('Extension',false,'jpg,png,gif');
        */
        $this->addElement('text', 'imgurl', array(
        	'label'=>'Image URL')
        );
        
        $this->addElement('text', 'opturl', array(
        	'label'=>'Optional URL')
        );
        
        $this->addElement('text', 'promocode', array(
        	'label'=>'Promo Code')
        );
        
        
        
        $this->addElement('text', 'startdate', array(
        	'label'=>'Start Date')
        );
        $this->addElement('text', 'enddate', array(
        	'label'=>'End Date')
        );
        $this->addElement('checkbox','nodisplay',array(
        	'label'=>'Do Not Display')
        );
        //$birthday = new Zend_Dojo_Form_Element_DateTextBox('birthday');
        //$birthday->setLabel('Birthday');
        

        //$this->addElements(array($birthday));
        
        
        $this->addElement('hidden', 'lang',array('value'=>'E'));
        $this->addElement('hidden', 'orig_couponid',array('value'=>''));
        
        
		
		
	}
	
	public function places()
	{
		$db = Zend_Registry::get('db');
		
        $sql = 'select location from places order by location';
        
        try {
        
        	$result = $db->fetchAssoc($sql);
        	
        }catch(Zend_Db_Adapter_Exception $e){
        	
        	//$this->view->error = $e;
        	
        }
        
		foreach($result as $i => $v){
        	$places[$i] = $v['location'];
        }
        
        return $places;
        
	}
	
public function tags($authority=0)
	{
		$db = Zend_Registry::get('db');
		
        $sql = 'select * from coupon_tags where authority = '.$authority;
        
        try {
        
        	$result = $db->fetchAssoc($sql);
        	
        }catch(Zend_Db_Adapter_Exception $e){
        	
        	//$this->view->error = $e;
        	
        }
        
		foreach($result as $i => $v){
        	$tag[$v['id']] = $v['tag'];
        }
        
        return $tag;
        
	}
	
}