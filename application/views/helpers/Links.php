<?php
class Zend_View_Helper_Links extends Zend_View_Helper_Abstract

{

    public function links($links,$file=false)

    {

        $html = '<ul>';
        
        foreach($links as $link){
        	if($file){
        		if(file_exists($link['file'])){
        			$html .= '<li><a target="_blank" href="'.$link['href'].'">'.$link['name'].'</a></li>';
        		}
        	}
        }

        $html .= '</ul>';

        return $html;

    }

}