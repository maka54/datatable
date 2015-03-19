<?php namespace Maka\Datatable\Libraries;

use \HTML;

class Element {
	
	private $value;
	private $length;
	private $route;
	private $parameters;
	private $ajax;
	
	public  function __construct( $route, $parameters, $value, $length, $ajax ){
		$this->value = $value;
		$this->length = $length;
		$this->route = $route;
		$this->parameters = (array) $parameters;
		$this->ajax = $ajax;

	}
	
	public function active(){
		return ($this->value == $this->length) ? true : false;
	}
	
	public function value(){
		return $this->value;
	}
	
	public function link(){
		$parameters = $this->parameters + ['length' => $this->value ];
		
		if( $this->ajax ){
			return link_to('#' , $this->value, ['data-href' => route(  $this->route, $parameters ) ]);
		}
		return link_to_route( $this->route, $this->value, $parameters);
	}

}