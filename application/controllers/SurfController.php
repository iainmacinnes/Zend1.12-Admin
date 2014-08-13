<?php

#ADDED UNIQUE INDEX ON SURF_CONDITIONS USING COLUMNS PARK AND REPORT_DATE

class SurfController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->db = Zend_Registry::get('db');
        $username = Zend_Registry::get('username');
        $acl = Zend_Registry::get('acl');
        if(FALSE == $acl->isAllowed($username, 'Surf')){
        	exit();
        }
        $front = zend_controller_front::getInstance();
    	$front->getResponse()->setHeader('Cache-Control', 'no-cache');
    	$front->getResponse()->setHeader('Pragma', 'no-cache');
    }

    public function indexAction()
    {	
    	//new day
    	//$this->cronAction();
    	
    	$select = $this->db->select()
	        	->from('surf_tides',
	        		array('*'))
	        	->where('date(cal_date) = date(now())')
	        	->where('beach = (select tide_id from surf_beaches where id = 1)');
	        try {
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        		$this->view->error = $e;
        	}
        	
	    $result = $stmt->fetchAll();
    	$first = (float) $result[0]['height'];
    	$second = (float) $result[1]['height'];
	    if($first < $second){
	    	array_unshift($result,array('cal_time'=>'00:00:00'));
    	}
    		//Zend_Debug::dump($result);
    	
        $surfeditor = new Application_Form_Surfeditor();
        $surfeditor->setAction("/admin/tourism/surf/insert");
        $cal_date = date('Y/m/d',time());
        $cal_time = date('H:i:s',time());
        //$db->select();
        $surfeditor->setDefaults(
        	array('on_duty'=>0,
        		'air_temp'=>21,
        		'water_temp'=>17,
        		'report_date'=>$cal_date,
        		'report_time'=>$cal_time,
        		'tide1'=>$result[0]['cal_time'],
        		'tide2'=>$result[1]['cal_time'],
        		'tide3'=>$result[2]['cal_time'],
        		'tide4'=>$result[3]['cal_time'],
        		'tide5'=>$result[4]['cal_time']        		
        	)
        );
        $this->view->form = $surfeditor;
    }

    public function insertAction()
    {
    	//Zend_Debug::dump($_POST);
    	//exit;
    	$form = new Application_Form_Surfeditor();
    	
    	if(isset($_POST['save'])){
	    	$var = $form->getValidValues($_POST);
	    	$var['report_date'] = date('Y/m/d',time());
        	$var['report_time'] = date('H:i:s',time());
    		try {
			   	$this->db->insert('surf_conditions',$var);
			   	$this->view->message = 'Success!';
			}catch(Exception $e){
			     $this->view->error = $e;
			     $this->view->message = $e;
			     
			}
			
			$this->render('editComplete');
    	}
    }
    
	public function editAction()
    {
    	$form = new Application_Form_Surfeditor();
    	if(isset($_POST['save'])){
    		
	    	$var = $form->getValidValues($_POST);
	    	$var['report_date'] = date('Y/m/d',time());
        	$var['report_time'] = date('H:i:s',time());
    		try {
			   	$this->db->update('surf_conditions',$var,'id='.$_POST['qid']);
			   	$this->view->message = 'Success!';
			}catch(Exception $e){
			     $this->view->error = $e;
			     $this->view->message = $e;
			}
			
			$this->render('editComplete');
			
    	}else{    		
    		$vars = $this->_request->getParams();
    		$qid = preg_replace('[\D]', '', $i);

	        $select = $this->db->select()
	        	->from('surf_conditions',
	        		array('*'))
	        	->where('id = '.$vars['qid']);
	        try {
        		$stmt = $this->db->query($select);
        	}catch(Zend_Db_Adapter_Exception $e){
        		$this->view->error = $e;
        	}
	       	$result = $stmt->fetchAll();
	        $surfeditor = new Application_Form_Surfeditor();
	        $surfeditor->setAction("/admin/tourism/surf/edit");
	        $surfeditor->setDefaults(
	        	$result[0]
	        );
	        
	        $cal_date = date('Y/m/d',time());
        	$cal_time = date('H:i:s',time());
        	/*
        	$tide1 = date('g:00',strtotime($result[0]['tide1']));
        	$tide2 = date('g:00',strtotime($result[0]['tide2']));
        	$tide3 = date('g:00',strtotime($result[0]['tide3']));
        	$tide4 = date('g:00',strtotime($result[0]['tide4']));
        	$tide5 = date('g:00',strtotime($result[0]['tide5']));
        	*/
        	$defaults = array(
	        		'report_time'=>$cal_time,
	        		'report_date'=>$cal_date,	
	        	);
	        //Zend_Debug::dump($defaults);
	        //exit;

	        //$db->select();
	        $surfeditor->setDefaults(
	        	$defaults
	        );
	        
	        $surfeditor->addElement('hidden', 'qid', array(
	        	'value'=>$vars['qid'])
	        );
	        $this->view->form = $surfeditor;
	        
    		$this->render('index');
    		
    	}
    }
    
	public function listAction()
    {
    	//$this->cronAction();
    	/*
    	$max_date = $this->db->select()
	        	->from(array('c'=>'surf_conditions'),array('rd'=>'max(report_date)'));

    	
    	$select = $this->db->select()
	        	->from(array('c'=>'surf_conditions'),
	        		array('c.report_date','c.park','c.air_temp','c.water_temp','c.condition','c.id'))
	        		->join(array('b'=>'surf_beaches'), 'b.id = c.park',array('b.beach_name'))
	        		->join(array('q'=>$max_date), 'q.rd = c.report_date',array('q.rd'))
	        		->where('q.rd = c.report_date');
	    */
	    $select = $this->db->select()
	        	->from(array('c'=>'surf_conditions'),
	        		array('c.report_date','c.park','c.air_temp','c.water_temp','c.condition','c.id'))
	        		->join(array('b'=>'surf_beaches'), 'b.id = c.park',array('b.beach_name'));	
	   	try {
	   		$stmt = $this->db->query($select);
	   		$result = $stmt->fetchAll();
	    	$this->view->result = $result;
        }catch(Zend_Db_Adapter_Exception $e){
        	$this->view->error = $e;
        } 
        //$this->render('results');
    }
    
	public function cronAction()
    {
    		
    	$num_recs = $this->db->fetchOne("select count(*) as num_recs from surf_tides where date(cal_date) = date(now());");
    	
    	if($num_recs>0){
    		return;
    	}
    	
	    set_time_limit(0);
	    
		### http://simplehtmldom.sourceforge.net
		require_once 'SimpleHtmlDom/simple_html_dom.php';
		
		$date = urlencode(date('Y/m/d',strtotime('today')));
		$url = 'http://www.waterlevels.gc.ca/eng/station';
		$beaches = array('Souris'=>1650,
			'Georgetown'=>1660,
			'Wood Islands'=>1680,
			'Alberton'=>1885,
			'West Point'=>1845,
			'Port Borden'=>1725,
			'Malpeque'=>1905
			);
		
		foreach($beaches as $beach){
		
			#http://www.waterlevels.gc.ca/eng/station?sid=1650&pres=2&date=2013%2F06%2F06
			#&type=0
			#&tz=ADT
		
			// Create DOM from URL or file
			$html = file_get_html("$url?sid=$beach&pres=2&date=$date");
		
			$element = $html->find('div[class=stationTextData] div');
			//var_dump($element);
			//exit;
		
			foreach($element as $e){
				//echo $e->innertext;
				$tide = explode(';',$e->innertext);
				
				if(is_array($tide)&&count($tide)==3){
					$cal_time = date('H:i:s', strtotime($tide[1]));
					$tide_date = trim($tide[0]);
					$tide_height = trim($tide[2]);
					$tides[] = "($beach,'$tide_date','$cal_time',$tide_height)";
				}
				
			}
		
		}
		
		$insert = "INSERT INTO surf_tides (beach,cal_date,cal_time,height) VALUES ".implode(',',$tides);
		
		$this->db->query($insert);
    }

}

