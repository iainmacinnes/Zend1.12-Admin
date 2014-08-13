<?php

class Application_Form_Surfeditor extends Zend_Form
{
	
	public function init(){
        
        
        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$this->addElement('submit', 'save', array('value'=>'Save'));
        
        $this->addElement('select','park',array(
        	'multioptions'=>array(
        		1=>'Basin Head',
        		2=>'Red Point',
        		3=>'Panmure Island',
        		4=>'Northumberland',
        		5=>'Jacques Cartier',
        		6=>'Cedar Dunes',
        		7=>'Chelton',
        		8=>'Cabot Beach'
        		),
        	'label'=>'Park'
        	//,'onchange'=>'javascript:getTide();'
        	)
        );
        
        $this->addElement('select','condition',array(
        	'multioptions'=>array(
        		'CALM'=>'CALM',
        		'CAUTION'=>'CAUTION',
        		'DANGER'=>'DANGER'
        		),
        	'label'=>'Surf Conditions')
        );
        
        $this->addElement('radio', 'on_duty', array(
        	'multioptions'=>array(
        		1=>'Yes',
        		0=>'No'),
        	'label'=>'On Duty')
        );
        
        for($i=40;$i>0;$i--){
        	$air_temps[$i] = $i; 
        }
        $this->addElement('select','air_temp',array(
        	'multioptions'=>$air_temps,
        	'label'=>'Air Temperature')
        );
        
        for($j=30;$j>0;$j--){
        	$water_temps[$j] = $j; 
        }
        $this->addElement('select','water_temp',array(
        	'multioptions'=>$water_temps,
        	'label'=>'Water Temperature')
        );
        /*
		for($k=0;$k<24;$k++){
			$idv = $k.":00";
        	$times[$idv] = $idv; 
        }
        */
        $this->addElement('text', 'tide1', array(
        	'label'=>'High Tide'
	        //,'multioptions'=>$times
	        )
        );
        
        $this->addElement('text', 'tide2', array(
        	'label'=>'Low Tide'
	        //,'multioptions'=>$times
	        )
        );
        
        $this->addElement('text', 'tide3', array(
        	'label'=>'High Tide'
	        //,'multioptions'=>$times
	        )
        );
        
        $this->addElement('text', 'tide4', array(
        	'label'=>'Low Tide'
	        //,'multioptions'=>$times
	        )
        );
        
        $this->addElement('text', 'tide5', array(
        	'label'=>'High Tide'
	        //,'multioptions'=>$times
	        )
        );
        
        $this->addElement('text', 'report_date', array(
        	'label'=>'Report Date',
        	'attribs'    => array(
        		'disabled' => 'disabled'
        		)
        	)
        );

        $this->addElement('text', 'report_time', array(
        	'label'=>'Report Time',
        	'attribs'    => array(
        		'disabled' => 'disabled'
        		)
        	)
        );
        
	}
	
}