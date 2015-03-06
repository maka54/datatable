<?php namespace Maka\Datatable\Libraries;

class Storage {
	
	private $length;
	private $page;
	private $sort;
	private $search;
	
	
	public  function __construct( $length = null, $page = 1, $sort = null, $search = null ){
		$this->length = $length;
		$this->page = $page;
		$this->sort = $sort;
		$this->search = $search;

	}
	
	public function push( $args = [] ){
		foreach( (array) $args as $key => $value){
			if(property_exists($this, $key)){
				$this->$key = $value;
			}
		}
	}
	
	public function __set( $key, $value ){
		if(property_exists($this, $key)){
			$this->$key = $value;
		}
	}
	
	public function __get( $key ){
		if(property_exists($this, $key)){
			return $this->$key;
		}
		return null;
	}
	
	public function toArray(){
		return (array) get_object_vars( $this );
	}


}