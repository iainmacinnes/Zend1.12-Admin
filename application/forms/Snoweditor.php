<?php

class Application_Form_Snoweditor extends Zend_Form
{
	
	private $db;
	
	public function init(){
		
		$this->db = Zend_Registry::get('db');
        $this->setAction("/admin/tourism/snow/edit");
        $this->addElement('submit', 'save', array('value'=>'Save'));
	}
	
	public function conditions($hill){
		$select = $this->db->select()
	        ->from(array('q'=>'snow_questions'),array('qtype','qname','qid'))
	        ->join(array('a'=>'snow_answers'),'q.aid = a.aid',array('atype','vals'))
	        ->join(array('v'=>'snow_values'),'v.qid = q.qid',array('val'))
	        ->where("qtype = '".$hill."'")
                ->order("q.ord");

	        	
	    try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	//$this->view->result = $result;
	    	//Zend_Debug::dump($result);
	    	
	    	foreach($result as $r){
	    		$ele = 'FormElement'.$r['qid'];
	    		$this->addElement($r['atype'], $ele, array(
		        	'label'=>$r['qname'],
	    			'value'=>$r['val'])
		        );  
		        $multis = array('select','radio');
		        if(in_array($r['atype'],$multis)){		        	
		        	$values = json_decode($r['vals'],true);
		        	//Zend_Debug::dump($values);
		        	$this->$ele->addMultiOptions($values);
		        }
	    	}
	    	
        }catch(Zend_Db_Adapter_Exception $e){
        	//$this->view->error = $e;
        } 
        
        
	}
}