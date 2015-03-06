<?php namespace Maka\Datatable\Libraries;

use \HTML;

class Element {
	
	private $value;
	private $length;
	private $route;
	private $ajax;
	
	public  function __construct( $route, $value, $length, $ajax ){
		$this->value = $value;
		$this->length = $length;
		$this->route = $route;
		$this->ajax = $ajax;

	}
	
	public function active(){
		return ($this->value == $this->length) ? true : false;
	}
	
	public function value(){
		return $this->value;
	}
	
	public function link(){
		if( $this->ajax ){
			return link_to('#' , $this->value, ['data-href' => route(  $this->route, ['length' => $this->value ] ) ]);
		}
		return link_to_route( $this->route, $this->value, ['length' => $this->value ]);
	}

}