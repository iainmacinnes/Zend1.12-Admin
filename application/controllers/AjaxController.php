<?php

class AjaxController extends Zend_Controller_Action
{
	public function init()
    {
    	//diable layout and view for ajax
		$this->_helper->layout ()->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( true );
    	$this->db = Zend_Registry::get('db');
    }


    public function operatorAction(){
    	$vars = $this->_request->getParams();
    	if(isset($vars['oid'])){
	        $select = $this->db->select()
	        	->from('API_operators',
	        		array('*'))
	        	->where('API_operators.id = '.$vars['oid']);
	        	//->join('API_rating','API_operators.id = API_rating.id')
	        try {
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        		$this->view->error = $e;
        	}
	       	$result = $stmt->fetchAll();
    	 	//create json from query results
		 	$json = Zend_Json::encode($result);
		 	//echo back json response
         	echo $json;
    	}
    }
}