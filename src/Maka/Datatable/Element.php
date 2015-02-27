<?php namespace Maka\Datatable;

use \HTML;

class Element {
	
	private $value;
	private $length;
	private $route;
	
	public  function __construct( $route, $value, $length ){
		$this->value = $value;
		$this->length = $length;
		$this->route = $route;

	}
	
	public function active(){
		return ($this->value == $this->length) ? true : false;
	}
	
	public function value(){
		return $this->value;
	}
	
	public function link(){
		return link_to_route( $this->route, $this->value, ['lgt' => $this->value ]);
	}

}