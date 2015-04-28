<?php namespace Maka\Datatable\Libraries;

use \HTML;
use Maka\Datatable\Datatable as Datatable;

class Row {
	
	protected $table;
	protected $attributes;
	
	public  function __construct( Datatable $table, $attributes = array() ){
		$this->table = $table;
		$this->attributes = $attributes;		
	}
	
	public function render( $e ){
		
		$attributes = $this->attributes;
		
		if ( is_callable($this->attributes) ){
			$attributes = call_user_func($this->attributes, $e);
		} 
		
		return $this->parseAttributes( $attributes );
	}
	
	private function parseAttributes( $attributes ){
		
		if(!$attributes )
			return null;
		
		$e = [];
		foreach ((array) $attributes  as $key => $value){
			if (is_numeric($key)) 
				$key = $value;

			if ( ! is_null($value)) 
				$e[] = "{$key}=\"{$value}\"";
		}
		
		return implode(' ', $e);
	}
		

}
