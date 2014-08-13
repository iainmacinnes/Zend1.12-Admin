<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
    	$username = Zend_Registry::get('username');
		$acl = Zend_Registry::get('acl');
    	#user privledge / Order sequence / 
    	#
    	/*
    	if($acl->isAllowed($username, 'Media Stories')){
	        $listviews = "$.get('news-media/list', {}, function(data) {
			    var response = $('<div />').html(data);
			    $('#mainform').append(response);
			},'html');";
    	}
    	
    	*/
   		if($acl->isAllowed($username, 'QTS')){
	        $listviews = "updateModule({'m':'#subform','u':'qts/find','t':'#mainform','e':'false'});";
    	}
		//$listviews = "";
        $this->view->list = "<script type=\"text/javascript\">".
        $listviews.
        "</script>";
    }

}